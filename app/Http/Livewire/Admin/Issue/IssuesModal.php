<?php

namespace App\Http\Livewire\Admin\Issue;

use App\Models\Issue;
use App\Models\Sprint;
use Livewire\Component;

class IssuesModal extends Component
{
    public $show = false;
    public $sprint;
    public $issues;
    public $selectedIssuesId = [];
    public $checkAll = false;

    protected $listeners = ['show'];

    public function show($id)
    {
        $this->sprint = Sprint::findOrFail($id);
        $this->issues = Issue::query()
            ->with('label')
            ->whereNull('sprint_id')
            ->where('project_id', $this->sprint->project_id)
            ->whereNull('parent_id')
            ->orderBy('order', 'asc')
            ->get();

        $this->show = true;
    }

    public function hide()
    {
        $this->show = false;
        $this->reset();
    }

    public function checkAllIssues()
    {
        $this->checkAll = !$this->checkAll;
        if ($this->checkAll) {
            return  $this->selectedIssuesId = $this->issues->pluck('id');
        }
        return $this->selectedIssuesId = [];
    }

    public function addToSprint()
    {
        if (!count($this->selectedIssuesId)) return session()->flash('message', __('choose at least one'));
        Issue::query()
            ->whereIn('id', $this->selectedIssuesId)
            ->orWhereIn('parent_id', $this->selectedIssuesId)
            ->update(['sprint_id' => $this->sprint->id]);
        $this->emitTo('admin.sprint.sprint-list', 'alertMessage', trans('Successfully added'));
        $this->hide();
    }

    public function render()
    {
        return view('livewire.admin.issue.issues-modal');
    }
}
