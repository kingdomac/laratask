<?php

namespace App\Http\Controllers\Admin;

use App\Models\Sprint;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class SprintController extends Controller
{
    public function index(Project $project)
    {
        return view('admin.projects.sprints.index', compact('project'));
    }

    public function edit(Project $project, Sprint $sprint)
    {
        return view('admin.projects.sprints.edit', compact('sprint', 'project'));
    }

    public function update(Project $project, Sprint $sprint)
    {
        $data = validator()->make(request()->all(), [
            'name' => ['required', Rule::unique('sprints', 'name')->where('project_id', $project->id)->ignore($sprint->id)],
            'start_date' => ['nullable', 'required_with:end_date', 'after_or_equal:' . Carbon::today()],
            'end_date' => ['nullable', 'date', 'after:start_date', 'before_or_equal:'  . $project->due_date]
        ])->validated();

        Sprint::query()
            ->where('project_id', $project->id)
            ->where('id', $sprint->id)
            ->update($data);
        return redirect()
            ->route('admin.projects.sprints.index', [$project->id])
            ->with('message', __('successfully updated'));
    }

    public function destroy($projectId, $id)
    {
        Sprint::where('project_id', $projectId)->where('id', $id)->delete();
        session()->flash('message', __('deleted successfully!'));
        return back();
    }
}
