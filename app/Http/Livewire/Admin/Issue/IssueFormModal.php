<?php

namespace App\Http\Livewire\Admin\Issue;

use App\Models\Issue;
use App\Models\Label;
use Livewire\Component;
use App\Enums\LabelEnum;
use App\Models\Priority;
use App\Enums\StatusEnum;
use App\Enums\PriorityEnum;
use App\Enums\RoleEnum;
use App\Http\Services\NotificationService;
use App\Models\Status;
use App\Models\User;
use App\Notifications\NewAssignedIssue;
use App\Notifications\NewCanceledIssue;
use App\Notifications\NewCompletedIssue;
use App\Notifications\NewIssueCreated;
use App\Notifications\NewVerifiedIssue;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rules\Enum;

class IssueFormModal extends Component
{
    public bool $show = false;
    public bool $submitted = false;

    public $project;
    public $parentId;

    public $issueId;
    public $issue;

    public $labelId = 1;
    public $priorityId;
    public $userId;
    public string $title = '';
    public $summary;

    public $storyPoint = 0;
    public $valuePoint = 0;

    public $statusId;
    public $timeSpent;
    public $completionPerc = 0;

    protected $listeners = ['show', 'hide'];

    protected function rules()
    {
        return [
            'labelId' => ['required', new Enum(LabelEnum::class)],
            'priorityId' => ['nullable', new Enum(PriorityEnum::class)],
            'statusId' => ['nullable', new Enum(StatusEnum::class)],
            'userId' => [
                'nullable',
                Rule::exists('users', 'id')->where(function ($query) {
                    return $query->where('role_id', '!=', RoleEnum::AGENT->value);
                })
            ],
            'title' => ['required'],
            'summary' => ['nullable', 'string'],
            'storyPoint' => ['nullable', 'integer'],
            'valuePoint' => ['nullable', 'integer'],
            'timeSpent' => ['nullable', 'integer'],
            'completionPerc' => ['nullable', 'integer', 'max:100'],
        ];
    }

    protected $messages = [
        'labelId.required' => 'Issue label is required.',
        'priorityId.required' => 'priority is required.',
        'completionPerc.max' => 'The max value is 100%.',
        'dueDate.date' => 'The date must be in date format mm/dd/YYYY',
        'storyPoint.integer' => 'The points must be a number',
        'valuePoint.integer' => 'The points must be a number',
        'timeSpent.integer' => 'The time must be a number',
    ];

    public function show($id = 0)
    {
        $this->issueId = $id;
        $this->resetForm();
        $this->loadIssue($id);
        $this->show = true;
    }


    public function hide()
    {
        $this->show = false;
        $this->resetForm();
    }

    public function loadIssue($id)
    {
        if (!$this->issueId) {
            return;
        }
        $issue = Issue::findOrFail($id);
        $this->issue = $issue;
        $this->title = $issue->title;
        $this->summary = $issue->details;
        $this->labelId = $issue->label_id;
        $this->priorityId = $issue->priority_id;
        $this->parentId = $issue->parent_id;
        $this->storyPoint = $issue->story_point;
        $this->valuePoint = $issue->value_point;
        $this->timeSpent = $issue->time_spent;
        $this->completionPerc = $issue->completion_perc;
        $this->statusId = $issue->status_id;
        $this->userId = $issue->user_id;
    }

    public function store()
    {
        if ($this->parentId) $this->labelId = LabelEnum::TASK->value;
        if (!auth()->user()->isSuperAdmin) return;

        // it means that a new story is always empty so it cannot be assigned
        if ($this->labelId == LabelEnum::STORY->value && $this->userId) {
            $this->resetErrorBag();
            return session()->flash('message', __('You cannot assign an new empty Story!'));
        }

        $this->validate();

        $insertedIssue  = DB::transaction(function () {
            $data = [
                'project_id' => $this->project->id,
                'created_by' => auth()->user()->id,
                'label_id' => $this->labelId,
                'title' => $this->title,
                'details' => $this->summary,
                'story_point' => $this->storyPoint,
                'value_point' => $this->valuePoint,
                'parent_id' => $this->parentId,
                'time_spent' => $this->timeSpent,
                'completion_perc' => $this->completionPerc,
                'assigned_by' => $this->userId ? auth()->user()->id : null,
            ];

            if ($this->userId) {
                $data['user_id'] = $this->userId;
            }

            if ($this->priorityId) {
                $data['priority_id'] = $this->priorityId;
            }

            if ($this->labelId != LabelEnum::STORY->value) {
                if ($this->statusId) {
                    $data['status_id'] = $this->userId ? StatusEnum::TODO->value : $this->statusId;
                }

                $data['is_new'] = true;
            }
            $data['order'] = (int)Issue::where('project_id', $this->project->id)->max('order') + 1;
            return Issue::create($data);
        });

        if ($insertedIssue->id) {
            // Send notifications to all super admins about -
            // a new issue has been created with the issue details
            $usersSuperAdmin = User::query()
                ->superAdmins()
                ->get();

            $issue = $insertedIssue->load('label', 'project');

            NotificationService::notifyUser($usersSuperAdmin, new NewIssueCreated($issue));

            if ($this->userId) {
                // Send notification to assigned user
                $assignedUser = User::findOrfail($this->userId);
                NotificationService::notifyUser($assignedUser, new NewAssignedIssue($issue));
            }

            $this->emitTo('admin.notification-menu', 'refreshCounter');
        }

        $this->resetErrorBag();
        $this->resetForm();
        $this->hide();

        $this->emitTo('admin.issue.issues-table', 'alertMessage', trans('Successfully created'));
        if ($this->parentId) $this->emitTo('admin.issue.issue-details', 'refresh');
    }

    public function update()
    {
        if (!auth()->user()->isSuperAdmin) return;

        $this->validate();
        $issue = Issue::with('assignedTo')->findOrFail($this->issueId);
        $newAssignedUser = null;
        $update = DB::transaction(function () use ($issue, &$newAssignedUser) {

            if ($this->statusId == StatusEnum::VERIFIED->value && $issue->status_id != StatusEnum::COMPLETED->value && $issue->status_id != StatusEnum::VERIFIED->value) {
                return session()->flash('message', __('The issue should be completed before being verified!'));
            }
            // Check if the issue is a story and has children,
            // you can only change the lable only if there is no child
            if ($issue->isStory && $issue->loadCount('children')->children_count) {
                if ($issue->label_id != $this->labelId) {
                    return session()->flash('message', __('You cannot change the label. Story is not empty!'));
                }
                // Cannot change the completion,
                // and always no assigned user for story only for subtasks
                if ($this->userId) {
                    // a new user being assigned
                    if ($issue->user_id != $this->userId) {
                        // add the story subtasks to history if it has substasks
                        //############## code here
                        $issue->children->map(function ($child) {
                            if ($child->user_id) {
                                $child->history()->attach($child->id, [
                                    'user_id' => $child->user_id,
                                    'assigned_by' => $child->assigned_by,
                                    'status_id' => $child->status_id,
                                    'time_spent' => $child->time_spent,
                                    'completion_perc' => $child->completion_perc
                                ]);
                            }
                        });
                        $newAssignedUser = User::findOrfail($this->userId);
                    } else {
                    }
                    $dataToUpdate = ['user_id' => $this->userId];
                    // // Check if story status is completed then update all children to 100%
                    // if ($this->statusId === StatusEnum::COMPLETED->value) {
                    //     $dataToUpdate['comletion_perc'] = 100;
                    //     $dataToUpdate['status_id'] = StatusEnum::COMPLETED->value;
                    // }
                    // Update all children to be assigned to new user
                    Issue::where('parent_id', $issue->id)
                        ->when(auth()->user()->isAdmin, function ($q) {
                            $q->where('user_id', auth()->user()->id);
                        })
                        ->update($dataToUpdate + ['is_new' => true]);
                }
            } else {
                if ($this->labelId === LabelEnum::STORY->value) {
                    $issue->completion_perc = null;
                    $issue->user_id = null;
                } else {
                    $issue->label_id = $this->labelId;

                    if ($this->statusId == StatusEnum::COMPLETED->value) {
                        $issue->completion_perc = 100;
                    } else {
                        $issue->completion_perc = $this->completionPerc;
                    }


                    $issue->status_id = $this->statusId;

                    // a new user has been assigned
                    if ($this->userId && $this->userId != $issue->user_id) {
                        //archive in history if it is not a story
                        $issue->history()->attach($issue->user_id, [
                            'assigned_by' => $issue->assigned_by,
                            'status_id' => $issue->status_id,
                            'time_spent' => $issue->time_spent,
                            'completion_perc' => $issue->completion_perc
                        ]);
                        $issue->is_new = true;
                        $issue->user_id = $this->userId ?? null;

                        $newAssignedUser = User::findOrfail($this->userId);
                    }

                    // if the issue isn't a subtask
                    if (!$issue->parent_id) {
                        $issue->story_point = $this->storyPoint;
                        $issue->value_point = $this->valuePoint;
                    }
                }
            }

            $issue->priority_id = $this->priorityId;
            $issue->title = $this->title;
            $issue->details = $this->summary;
            $issue->time_spent = $this->timeSpent;
            $issue->assigned_by = auth()->user()->id;
            $issue->updated_by = auth()->user()->id;

            return $issue->save();
        });

        if ($update) {
            $usersSuperAdmin = User::query()
                ->superAdmins()
                ->get();
            $issue->refresh()->load('label', 'project');
            // Send notification to assigned user
            if ($newAssignedUser) {
                NotificationService::notifyUser($newAssignedUser, new NewAssignedIssue($issue));
            }
            if ($issue->wasChanged('status_id') && $issue->status_id == StatusEnum::COMPLETED->value) {
                NotificationService::notifyUser($usersSuperAdmin, new NewCompletedIssue($issue));
            }
            if ($issue->wasChanged('status_id') && $issue->status_id == StatusEnum::VERIFIED->value) {
                if ($issue->assignedTo->id) {
                    NotificationService::notifyUser($issue->assignedTo, new NewVerifiedIssue($issue));
                }
                NotificationService::notifyUser($usersSuperAdmin, new NewVerifiedIssue($issue));
            }

            if ($issue->wasChanged('status_id') && $issue->status_id == StatusEnum::CANCELED->value) {
                if ($issue->assignedTo->id) {
                    NotificationService::notifyUser($issue->assignedTo, new NewCanceledIssue($issue));
                }
                NotificationService::notifyUser($usersSuperAdmin, new NewCanceledIssue($issue));
            }
            $this->resetForm();
            $this->hide();
            $this->emitUp('refresh');
            return $this->emitTo('admin.issue.issues-table', 'alertMessage', trans('Successfully updated'));
        }
    }

    public function resetForm()
    {
        $this->resetValidation();
        $this->reset('labelId');
        $this->reset('priorityId');
        $this->reset('userId');
        $this->reset('title');
        $this->reset('summary');
        $this->reset('storyPoint');
        $this->reset('valuePoint');
        $this->reset('statusId');
        $this->reset('timeSpent');
        $this->reset('completionPerc');
    }

    public function render()
    {
        $labels = Label::query()
            ->when($this->parentId, function ($query) {
                $query->where('id', '!=', LabelEnum::STORY->value);
            }, function ($query) {
                $query->when($this->issueId && $this->issue->loadCount('children')->children_count, function ($q) {
                    $q->where('id', '=', LabelEnum::STORY->value);
                });
            })
            ->get();

        $statuses = Status::all();
        $priorities = Priority::all();
        $users = User::where('role_id', '!=', RoleEnum::AGENT)->where('id', '!=', auth()->user()->id)->orderBy('name')->get();
        return view('livewire.admin.issue.issue-form-modal', compact('labels', 'statuses', 'priorities', 'users'));
    }
}