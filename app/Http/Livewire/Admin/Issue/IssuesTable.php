<?php

namespace App\Http\Livewire\Admin\Issue;

use App\Models\Issue;
use App\Models\Label;
use App\Models\Status;
use App\Enums\RoleEnum;
use App\Enums\StatusEnum;
use App\Http\Services\IssueService;
use App\Http\Traits\WithAlert;
use Livewire\Component;
use App\Models\Priority;
use App\Http\Traits\WithSorting;
use App\Models\Sprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

class IssuesTable extends Component
{
    use WithSorting, WithAlert;

    public $project;
    public $parentId;
    public $labels;
    public $priorities;
    public $statuses;
    public string $keyword = '';

    public $showDeleteModal = false;
    public $issueDeleteId = null;
    public $selectedIssue = 0;

    public $selectedLabels = [];
    public $selectedPriorities = [];
    public $selectedStatuses = [];
    public int $selectedUser = 0;

    //query string
    public $userId;
    public $sprintId;

    protected $listeners = ['refresh' => 'render', 'alertMessage'];
    protected $queryString = ['userId', 'sprintId'];

    public function mount()
    {
        $this->labels = Label::all();
        $this->priorities = Priority::all();
        $this->statuses = Status::all();
    }

    public function showDeleteModal($issueId)
    {
        $this->issueDeleteId = $issueId;
        $this->showDeleteModal = true;
    }

    public function hideDeleteModal()
    {
        $this->issueDeleteId = null;
        $this->showDeleteModal = false;
    }

    public function delete()
    {
        $issue = Issue::findOrFail($this->issueDeleteId);
        $issue->delete();
        $this->hideDeleteModal();
        session()->flash('message', trans('Successfully deleted'));
        if ($this->parentId) $this->emitTo('admin.issue.issue-details', 'refresh');
    }

    public function removeSprint($issueId)
    {
        abort_if(!auth()->user()->isSuperAdmin, Response::HTTP_FORBIDDEN);

        return Issue::query()
            ->where('id', $issueId)
            ->orWhere('parent_id', $issueId)
            ->update(['sprint_id' => null]);
    }

    public function render(): View
    {
        if ($this->sprintId) {
            $sprint = Sprint::findOrFail($this->sprintId);
        } else {
            $sprint = new Sprint();
        }

        $issueService = new IssueService();
        $issueService->projectId = $this->project->id;
        $issueService->userId = $this->userId;
        $issueService->parentId = $this->parentId;
        $issueService->sprintId = $this->sprintId;
        $issueService->keyword = $this->keyword;
        $issueService->sortDirection = $this->sortDirection;
        $issueService->sortBy = $this->sortBy;
        $issueService->selectedLabels = $this->selectedLabels;
        $issueService->selectedPriorities = $this->selectedPriorities;
        $issueService->selectedStatuses = $this->selectedStatuses;
        $issueService->selectedUser = $this->selectedUser;

        $issues = $issueService->getIssues();

        return view('livewire.admin.issue.issues-table', compact('issues', 'sprint'));
    }
}
