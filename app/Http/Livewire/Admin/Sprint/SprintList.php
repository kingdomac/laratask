<?php

namespace App\Http\Livewire\Admin\Sprint;

use App\Models\Sprint;
use Livewire\Component;
use App\Enums\LabelEnum;
use App\Enums\StatusEnum;
use App\Http\Traits\WithAlert;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class SprintList extends Component
{
    use WithAlert;

    public $project;
    public $name;
    public $start_date;
    public $end_date;

    public $showIssuesModal = false;
    protected $listeners = ['refresh' => 'render', 'alertMessage'];

    public function showIssuesModal(int $sprintId)
    {
        $this->emitTo('admin.issue.issues-modal', "show", $sprintId);
        $this->showIssuesModal = true;
    }

    public function create()
    {
        $validatedData = $this->validate([
            'name' => ['required', Rule::unique('sprints', 'name')->where('project_id', $this->project->id)],
            'start_date' => ['nullable', 'required_with:end_date', 'after_or_equal:' . Carbon::today()],
            'end_date' => ['nullable', 'date', 'after:start_date', 'before_or_equal:'  . $this->project->due_date]
        ]);

        $validatedData['project_id'] = $this->project->id;
        Sprint::create($validatedData);
        $this->resetForm();
        return session()->flash('message', __('created successfully!'));
    }

    private function resetForm()
    {
        $this->reset([
            'name',
            'start_date',
            'end_date'
        ]);
    }


    public function render()
    {
        $sprints = Sprint::query()
            ->when(auth()->user()->isAdmin, function ($q) {
                $q->withCount(['issues as verified_issues_count' => function ($q) {
                    $q->whereIn('status_id', [StatusEnum::COMPLETED->value, StatusEnum::VERIFIED->value]);
                    $q->where('label_id', '!=', LabelEnum::STORY->value);
                    $q->byUserId();
                }])
                    ->withCount(['issues as active_issues_count' => function ($q) {
                        $q->where('status_id', '!=', StatusEnum::CANCELED->value);
                        $q->where('label_id', '!=', LabelEnum::STORY->value);
                        $q->byUserId();
                    }])
                    ->withCount(['issues' => function ($q) {
                        $q->whereNull('parent_id');
                        $q->where('user_id', auth()->user()->id);
                        $q->orWhere(function ($q) {
                            $q->whereIn('id', function ($q) {
                                $q->select('parent_id')->from('issues');
                                $q->where('user_id', auth()->user()->id);
                            });
                        });
                    }]);

                $q->whereHas('issues', function ($q) {
                    $q->byUserId();
                });
            }, function ($query) {
                $query->withCount(['issues as verified_issues_count' => function ($q) {
                    $q->where('status_id', StatusEnum::VERIFIED->value);
                    $q->where('label_id', '!=', LabelEnum::STORY->value);
                }])
                    ->withCount(['issues as active_issues_count' => function ($q) {
                        $q->where('status_id', '!=', StatusEnum::CANCELED->value);
                        $q->where('label_id', '!=', LabelEnum::STORY->value);
                    }])
                    ->withCount(['issues' => function ($q) {
                        $q->whereNull('parent_id');
                    }]);
            })
            ->where('project_id', $this->project->id)
            ->get();

        return view('livewire.admin.sprint.sprint-list', compact('sprints'));
    }
}
