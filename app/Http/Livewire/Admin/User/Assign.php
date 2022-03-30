<?php

namespace App\Http\Livewire\Admin\User;

use App\Models\User;
use App\Models\Issue;
use App\Enums\RoleEnum;
use Livewire\Component;
use App\Enums\LabelEnum;
use App\Enums\StatusEnum;
use App\Http\Traits\WithAlert;
use Illuminate\Support\Facades\DB;
use App\Notifications\NewAssignedIssue;
use App\Http\Services\NotificationService;

class Assign extends Component
{
    use WithAlert;

    public $issueId;
    public bool $showAssignModal = false;
    public $keyword = '';

    protected $listeners = ['show'];

    public function show($issueId)
    {

        $this->issueId = $issueId;
        $this->showAssignModal = true;
    }

    public function hide()
    {
        $this->showAssignModal = false;
        $this->keyword = '';
    }

    public function assign($userId = null)
    {
        if (!auth()->user()->isSuperAdmin)  return;

        $issue = Issue::withCount('children')->findOrFail($this->issueId);
        if ($issue->user_id === $userId) {
            return session()->flash('message', trans('Selected user is already assigned'));
        }
        $user = new User();
        if ($userId) $user = User::findOrFail($userId);

        if ($issue->label_id == LabelEnum::STORY->value) {
            if (!$issue->children_count) return session()->flash('message', __('You cannot assign an empty Story!'));

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

            if (!$user->id) $issue->where('parent_id', $this->issueId)->update(['user_id' => null]);
            if ($user->id) {
                Issue::query()->where('parent_id', $issue->id)->update(['user_id' => $user->id, 'assigned_by' => auth()->user()->id, 'status_id' => StatusEnum::TODO, 'is_new' => true]);
                NotificationService::notifyUser($user, new NewAssignedIssue($issue->load('label', 'project')));
            }
        } else {
            // Add the old user to history
            DB::transaction(function () use ($issue, $user) {
                // Check if the issue has been assigned before and add it to the history
                if ($issue->user_id) {
                    $issue->history()->attach($issue->id, [
                        'user_id' => $issue->user_id,
                        'assigned_by' => $issue->assigned_by,
                        'status_id' => $issue->status_id,
                        'time_spent' => $issue->time_spent,
                        'completion_perc' => $issue->completion_perc
                    ]);
                }

                // unassign the user
                if (!$user->id) {
                    Issue::query()->where('id', $issue->id)->update(['user_id' => null, 'assigned_by' => null, 'status_id' => null, 'is_new' => 0]);
                } else {
                    //dd(['user_id' => $user->id, 'assigned_by' => auth()->user()->id, 'status_id' => StatusEnum::TODO->value, 'is_new' => true]);
                    Issue::query()->where('id', $issue->id)->update(['user_id' => $user->id, 'assigned_by' => auth()->user()->id, 'status_id' => StatusEnum::TODO->value, 'is_new' => true]);
                    NotificationService::notifyUser($user, new NewAssignedIssue($issue->refresh()->load('label', 'project')));
                    $this->dispatchBrowserEvent('increaseNotficationCount-' . auth()->user()->id);
                }
            });
        }

        $this->emitTo('admin.issue.issues-table', 'alertMessage', trans('Successfully assigned'));
        $this->hide();
    }

    public function render()
    {

        $issue = Issue::with('label')->find($this->issueId);
        $users = User::query()->with('role')->whereNotIn('role_id', [RoleEnum::AGENT])

            ->when($this->keyword, function ($query) {
                return $query->where('name', 'like', $this->keyword . '%')->get();
            });
        return view('livewire.admin.user.assign', compact('users', 'issue'));
    }
}
