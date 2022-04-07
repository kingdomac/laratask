<?php

namespace App\Http\Services;

use App\Models\Issue;
use App\Enums\RoleEnum;
use App\Enums\LabelEnum;
use App\Enums\StatusEnum;

class IssueService
{
    public $projectId;
    public $userId;
    public $parentId;
    public $sprintId;
    public $keyword;
    public $sortDirection;
    public $sortBy;
    public $selectedLabels = [];
    public $selectedPriorities = [];
    public $selectedStatuses = [];
    public $selectedUser;

    public function getIssues()
    {
        $parentId = $this->parentId;
        $userId = $this->userId;

        return Issue::query()
            ->selectRaw('issues.*, (value_point/story_point) as bftb')
            ->with(['project', 'label', 'priority', 'status', 'assignedTo', 'sprint', 'children.assignedTo:id,name'])
            ->when(auth()->user()->isAdmin || $userId, function ($query) use ($parentId, $userId) {
                $query->withCount(['children' => fn ($q) => $q->byUserId($userId)]);

                // Count issues per status
                $query->withCount([
                    'children as todo_count' => fn ($q) =>
                    $q->byUserId($userId)
                        ->where('status_id', StatusEnum::TODO->value)
                ])
                    ->withCount([
                        'children as pending_count' =>
                        fn ($q) =>
                        $q->byUserId($userId)
                            ->where('status_id', StatusEnum::PENDING->value)
                    ])

                    ->withCount([
                        'children as inprogress_count' =>
                        fn ($q) =>
                        $q->byUserId($userId)
                            ->where(
                                'status_id',
                                StatusEnum::IN_PROGRESS->value
                            )
                    ])

                    ->withCount([
                        'children as completed_count' =>
                        fn ($q) =>
                        $q->byUserId($userId)
                            ->where(
                                'status_id',
                                StatusEnum::COMPLETED->value
                            )
                    ])

                    ->withCount([
                        'children as verified_count' =>
                        fn ($q) =>
                        $q->byUserId($userId)
                            ->where(
                                'status_id',
                                StatusEnum::VERIFIED->value
                            )
                    ])

                    ->withCount([
                        'children as canceled_count' =>
                        fn ($q) =>
                        $q->byUserId($userId)
                            ->where(
                                'status_id',
                                StatusEnum::CANCELED->value
                            )
                    ])

                    ->withCount(['children as active_issues_count' => function ($query) use ($userId) {
                        $query->where(function ($q) {
                            $q->where('status_id', '!=', StatusEnum::CANCELED->value)
                                ->orWhereNull('status_id');
                        });
                        $query->byUserId($userId);
                        $query->where('label_id', '!=', LabelEnum::STORY->value);
                    }]);


                $query->withCount([
                    'children as count_new_children' =>
                    fn ($q) =>
                    $q->byUserId($userId)
                        ->where('is_new', true)
                ]);

                $query->where(function ($query) use ($parentId, $userId) {
                    $query->where(function ($q) use ($parentId, $userId) {
                        $q->when($parentId, function ($q)  use ($parentId) {
                            $q->where('parent_id', $parentId);
                        }, function ($q) {
                            $q->whereNull('parent_id');
                        });
                        $q->byUserId($userId);
                    });
                    $query->orWhere(function ($q) use ($userId) {
                        $q->whereIn('id', function ($q) use ($userId) {
                            $q->select('parent_id')->from('issues')
                                ->where('user_id', auth()->user()->isAdmin ? auth()->user()->id : $userId);
                        });
                    });
                });
            }, function ($query) {
                $query->withCount(['children'])
                    // Count issues per status
                    ->withCount(['children as pending_count' => function ($q) {
                        $q->where('status_id', StatusEnum::PENDING->value);
                    }])

                    ->withCount(['children as todo_count' => function ($q) {
                        $q->where('status_id', StatusEnum::TODO->value);
                    }])

                    ->withCount(['children as inprogress_count' => function ($q) {
                        $q->where('status_id', StatusEnum::IN_PROGRESS->value);
                    }])

                    ->withCount(['children as completed_count' => function ($q) {
                        $q->where('status_id', StatusEnum::COMPLETED->value);
                    }])

                    ->withCount(['children as verified_count' => function ($q) {
                        $q->where('status_id', StatusEnum::VERIFIED->value);
                    }])

                    ->withCount(['children as canceled_count' => function ($q) {
                        $q->where('status_id', StatusEnum::CANCELED->value);
                    }])


                    ->withCount(['children as count_new_children' => function ($q) {
                        $q->where('is_new', true);
                    }])


                    ->withCount(['children as active_issues_count' => function ($query) {
                        $query->where(function ($q) {
                            $q->where('status_id', '!=', StatusEnum::CANCELED->value)
                                ->orWhereNull('status_id');
                        });
                        $query->where('label_id', '!=', LabelEnum::STORY->value);
                    }]);
            })

            ->when($this->projectId, function ($q) {
                $q->where('project_id', $this->projectId);
            })

            ->when($this->sprintId, fn ($query) => $query->where('sprint_id', $this->sprintId))

            ->when(count($this->selectedLabels), function ($query) {
                $query->whereIn('label_id', $this->selectedLabels);
            })

            ->when($this->keyword, function ($query) {
                $query->where('title', 'like', '%' . $this->keyword . '%');
            })

            ->when(count($this->selectedPriorities), function ($query) {
                $query->whereIn('priority_id', $this->selectedPriorities);
            })

            ->when(count($this->selectedStatuses), function ($query) {

                $query->where(function ($q) {
                    $q->whereIn('status_id', $this->selectedStatuses);
                    if (in_array('new', $this->selectedStatuses)) {
                        $q->orWhereNull('status_id');
                    }
                });
            })

            ->when($this->selectedUser, function ($query) {
                $query->where('user_id', $this->selectedUser);
            })

            ->when($parentId, function ($query) use ($parentId) {
                $query->where('issues.parent_id', $parentId);
            }, function ($query) {
                $query->whereNull('issues.parent_id');
            })

            ->when($this->sortBy && $this->sortBy != 'order', function ($query) {
                $query->orderBy($this->sortBy, $this->sortDirection);
            }, function ($query) {
                $query->orderBy('order', 'asc');
            })

            ->get();
    }

    public static function loadIssue($issueId)
    {
        return Issue::query()
            ->with('label', 'priority', 'status', 'parent.label', 'creator', 'updater', 'assigner', 'sprint')
            ->when(auth()->user()->isAdmin, function ($query) {
                $query->withCount(['children' => function ($q) {
                    $q->where(function ($q) {
                        $q->where('user_id', auth()->user()->id)
                            ->orWhere('issues.user_id', auth()->user()->id);
                    });
                }]);

                $query->withAvg(['children' => function ($q) {

                    $q->where(function ($q) {
                        $q->whereNull('status_id')
                            ->orWhere('status_id', '!=', StatusEnum::CANCELED->value);
                    });
                    $q->where(function ($q) {
                        $q->where('user_id', auth()->user()->id)
                            ->orWhere('issues.user_id', auth()->user()->id);
                    });
                }], 'completion_perc');
            }, function ($query) {
                $query->withCount('children')
                    ->withCount(['children as verified_count' => function ($query) {
                        $query->where('status_id', StatusEnum::VERIFIED->value);
                    }])
                    ->withCount(['children as active_issues_count' => function ($query) {
                        $query->where(function ($q) {
                            $q->where('status_id', '!=', StatusEnum::CANCELED->value)
                                ->orWhereNull('status_id');
                        });
                        $query->where('label_id', '!=', LabelEnum::STORY->value);
                    }])
                    ->withAvg(['children' => function ($query) {
                        $query->where(function ($q) {
                            $q->whereNull('status_id')
                                ->orWhere('status_id', '!=', StatusEnum::CANCELED->value);
                        });
                    }], 'completion_perc');
            })
            ->where('issues.id', $issueId)
            ->first();
    }
}
