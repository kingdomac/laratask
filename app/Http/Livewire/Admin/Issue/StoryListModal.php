<?php

namespace App\Http\Livewire\Admin\Issue;

use App\Models\Issue;
use Livewire\Component;
use App\Enums\LabelEnum;
use App\Enums\StatusEnum;
use Illuminate\Support\Facades\DB;

class StoryListModal extends Component
{
    public $showModal = false;
    public $issueId;
    public $issue;

    public $keyword;

    protected $listeners = ['show'];

    public function show($issueId)
    {
        $this->issueId = $issueId;
        $this->showModal = true;
    }

    public function hide()
    {
        $this->showModal = false;
    }

    public function assignToStory($storyId = null)
    {
        $this->issue->parent_id = $storyId ?? null;
        $this->issue->save();

        // $story = Issue::findOrFail($storyId);
        // $issueCompletion = DB::table('issue_user')
        //     ->selectRaw('sum(completion_perc)/count(issue_user.id) as completion_perc')
        //     ->whereRaw('issue_id in (select issues.id from issues where parent_id = ? and issue_user.id=(select max(id) from issue_user where issue_user.issue_id=issues.id))', [$storyId])
        //     ->first();
        // if ($story->assignedUsers->first()) {
        //     DB::table('issue_user')->where('id', $story->assignedUsers->first()->pivot->id)
        //         ->when($issueCompletion->completion_perc === 100, function ($q) {
        //             $q->update(['status_id' => StatusEnum::COMPLETED]);
        //         })
        //         ->update(['completion_perc' => $issueCompletion->completion_perc]);
        // }


        $this->emitTo('admin.issue.issues-table', 'refresh');
        $this->reset();
        $this->hide();
    }

    public function render()
    {
        $issue = Issue::find($this->issueId);
        $this->issue = $issue;
        $stories = Issue::where('label_id', LabelEnum::STORY)
            ->when($this->issueId, function ($q) use ($issue) {
                $q->where('project_id', $issue->project_id);
            })
            ->when($this->keyword, function ($q) {
                return $q->where('title', 'like', $this->keyword . '%');
            })
            ->get();
        return view('livewire.admin.issue.story-list-modal', compact('stories'));
    }
}
