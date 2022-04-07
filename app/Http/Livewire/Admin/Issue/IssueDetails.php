<?php

namespace App\Http\Livewire\Admin\Issue;

use App\Models\User;
use App\Models\Issue;
use App\Models\Status;
use App\Enums\RoleEnum;
use Livewire\Component;
use App\Enums\LabelEnum;
use App\Enums\StatusEnum;
use Illuminate\Support\Facades\DB;
use App\Http\Services\IssueService;
use App\Notifications\NewCompletedIssue;
use App\Http\Services\NotificationService;

class IssueDetails extends Component
{
    public $project;
    public $issueId;
    public $issue;
    public $statuses = [];

    public int $statusId = 0;
    public $storyPoint;
    public $valuePoint;
    public $completionPerc;

    protected $listeners = ['refresh' => 'render'];

    public function changeStatus()
    {
        if ($this->statusId == 0) return;


        $issue = Issue::find($this->issueId);

        if ($issue->isStory) return;

        if (
            auth()->user()->isAdmin
            && ($issue->status_id == StatusEnum::CANCELED->value
                || $issue->status_id == StatusEnum::VERIFIED->value)
        ) return;

        if ($issue->status_id != StatusEnum::COMPLETED->value && $this->statusId == StatusEnum::VERIFIED->value) {
            return session()->flash('message', __('The issue should be completed before being verified!'));
        };

        if ($this->statusId == StatusEnum::COMPLETED->value) {
            $issue->completion_perc = 100;
        }

        $issue->status_id = $this->statusId;

        $issue->save();

        $usersSuperAdmin = User::query()
            ->superAdmins()
            ->get();
        if ($issue->wasChanged('status_id') && $issue->status_id == StatusEnum::COMPLETED->value) {

            NotificationService::notifyUser($usersSuperAdmin, new NewCompletedIssue($issue));
        }

        $this->reset('statusId');
    }

    public function updatedStoryPoint($value)
    {
        if (auth()->user()->isSuperAdmin) {
            if (!$this->storyPoint || $this->storyPoint == '') $this->storyPoint = null;
            $this->issue =  Issue::findOrFail($this->issueId);
            $this->issue->story_point = (int)$this->storyPoint ?? null;
            $this->issue->save();
        }
    }

    public function updatedCompletionPerc($value)
    {
        if (
            auth()->user()->isAdmin
            && ($this->issue->status_id == StatusEnum::CANCELED->value
                || $this->issue->status_id == StatusEnum::VERIFIED->value)
        ) return;

        if ($this->issue->isStory) return;
        if ($this->completionPerc == 100) $this->issue->status_id = StatusEnum::COMPLETED;
        $this->validate([
            'completionPerc' => 'required|numeric|min:0|max:100'
        ]);

        $this->issue->completion_perc = $this->completionPerc;

        $this->issue->save();
        $this->reset('completionPerc');
    }

    public function updatedValuePoint($value)
    {
        if (auth()->user()->isSuperAdmin) {
            if (!$this->valuePoint || $this->valuePoint == '') $this->valuePoint = null;
            $issue =  Issue::findOrFail($this->issueId);
            $issue->value_point = (int)$this->valuePoint ?? null;
            $issue->save();
        }
    }

    public function render()
    {
        $this->issue = IssueService::loadIssue($this->issueId);
        $this->storyPoint = $this->issue->story_point;
        $this->valuePoint = $this->issue->value_point;
        if ($this->issue->is_new && $this->issue->user_id === auth()->id()) {
            $this->issue->is_new = 0;
            $this->issue->save();
        }
        $this->statuses = Status::where('id', '!=', $this->issue->status_id)->get();

        return view('livewire.admin.issue.issue-details');
    }
}
