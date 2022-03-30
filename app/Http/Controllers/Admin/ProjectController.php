<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Sprint;
use App\Models\Status;
use App\Enums\RoleEnum;
use App\Models\Project;
use App\Enums\LabelEnum;
use App\Enums\StatusEnum;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\CreateProjectRequest;
use Asantibanez\LivewireCharts\Models\PieChartModel;
use Asantibanez\LivewireCharts\Models\AreaChartModel;
use Asantibanez\LivewireCharts\Models\LineChartModel;
use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;
use Asantibanez\LivewireCharts\Models\TreeMapChartModel;

class ProjectController extends Controller
{

    public function index(): View
    {
        $userId = request('userId');

        $projects = Project::query()->conditions($userId)
            ->orderBy('due_date', 'desc')
            ->paginate(15);

        return view('admin.projects.index', compact('projects'));
    }

    public function chart(Project $project)
    {
        $statuses = Status::all()->pluck('color', 'id');

        //$minDate = Issue::whereNotNull('status_id')->where('project_id', $project->id)->min('created_at');
        // $minDate = Carbon::parse($minDate)->format('Y-m-d');

        // dd($project->created_at->format('Y-m-d'));




        $issuesPieChart = $project->loadCount([
            'issues as total_count' => fn ($q) => $q->where('label_id', '!=', LabelEnum::STORY->value),
            'issues as completed' => function ($query) {
                $query->where('status_id', StatusEnum::COMPLETED->value);
            }, 'issues as verified' => function ($query) {
                $query->where('status_id', StatusEnum::VERIFIED->value);
            }, 'issues as inprogress_count' => function ($query) {
                $query->where('status_id', StatusEnum::IN_PROGRESS->value);
            }, 'issues as pending_count' => function ($query) {
                $query->where('status_id', StatusEnum::PENDING->value);
            }, 'issues as todo_count' => function ($query) {
                $query->where('status_id', StatusEnum::TODO->value);
            }, 'issues as canceled_count' => function ($query) {
                $query->where('status_id', StatusEnum::CANCELED->value);
            }, 'issues as unassigned_count' => function ($query) {
                $query->whereNull('status_id')->where('label_id', '!=', LabelEnum::STORY->value);
            }
        ]);

        $pieChartModel = (new PieChartModel())
            ->setTitle('By Status | Total: ' . $issuesPieChart->total_count)
            ->addSlice('verified', $issuesPieChart->verified, $statuses[StatusEnum::VERIFIED->value])
            ->addSlice('completed', $issuesPieChart->completed, $statuses[StatusEnum::COMPLETED->value])
            ->addSlice('in progress', $issuesPieChart->inprogress_count, $statuses[StatusEnum::IN_PROGRESS->value])
            ->addSlice('pending', $issuesPieChart->pending_count, $statuses[StatusEnum::PENDING->value])
            ->addSlice('todo', $issuesPieChart->todo_count, $statuses[StatusEnum::TODO->value])
            ->addSlice('canceled', $issuesPieChart->canceled_count, $statuses[StatusEnum::CANCELED->value])
            ->addSlice('unassigned', $issuesPieChart->unassigned_count, '#99a095');

        $issuescolumnChart = Sprint::query()
            ->withSum(['issues as verified_point_count' => function ($q) {
                $q->where('status_id', 5);
            }, 'issues as undone_point_count' => function ($q) {
                $q->whereNotIn('status_id', [StatusEnum::CANCELED->value, StatusEnum::VERIFIED->value]);
            }], 'story_point')
            ->where('project_id', $project->id)
            ->get();

        $columnChartModel  = LivewireCharts::multiColumnChartModel()->setTitle('story point')->setDataLabelsEnabled(true)
            ->setColors(['#3f931b', '#f44336']);
        if (count($issuescolumnChart)) {
            $issuescolumnChart->each(function ($row) use ($columnChartModel) {
                $columnChartModel->addSeriesColumn('done', $row->name, $row->verified_point_count ?? 0);
                $columnChartModel->addSeriesColumn('undone', $row->name, $row->undone_point_count ?? 0);
            });
            $columnChartModel->withGrid();
        }



        return view('admin.projects.chart', compact('project', 'columnChartModel', 'issuescolumnChart', 'pieChartModel'));
    }


    public function create()
    {
        //abort_if(!auth()->user()->isSuperAdmin, Response::HTTP_FORBIDDEN);

        $agents = User::where('role_id', RoleEnum::AGENT->value)->get();
        $project = new Project();
        return view('admin.projects.create', compact('agents', 'project'));
    }


    public function store(CreateProjectRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['created_by'] = auth()->user()->id;
        Project::insert($data);
        return redirect()->route('admin.projects.index')->with('message', __('Successfully created.'));
    }


    public function show(Project $project)
    {
        $issues = $project->issues()->addSelect(DB::raw('issues.*, (value_point/story_point) as bftb'))->with(['label', 'priority'])->orderByRaw('(value_point/story_point) desc, priority_id asc')->get();
        return view('admin.projects.show', compact('project', 'issues'));
    }


    public function edit(Project $project)
    {
        $agents = User::where('role_id', RoleEnum::AGENT->value)->get();
        return view('admin.projects.create', compact('project', 'agents'));
    }


    public function update(CreateProjectRequest $request, Project $project)
    {
        $data = $request->validated();
        //dd($data);
        $project->update($data);
        return redirect()->route('admin.projects.index')->with('message', __('Successfully updated.'));
    }


    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('admin.projects.index')->with('message', __('Successfully deleted.'));
    }
}
