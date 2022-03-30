<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Issue;
use Illuminate\Http\Response;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Project $project)
    {

        return view('admin.projects.issues.index', compact('project'));
    }

    public function sort()
    {
        $sortableIds =  request('sortableIds');
        collect($sortableIds)->each(function ($id, $key) {
            $update = Issue::whereId($id)->update(['order' => (int)($key + 1)]);
            if (!$update) {
                return false;
            }
        });

        echo __('sorting success');
    }


    public function show(Project $project, Issue $issue)
    {

        abort_if(
            !auth()->user()->isSuperAdmin
                && auth()->user()->id != $issue->user_id
                &&
                !in_array(
                    auth()->user()->id,
                    $issue->children->pluck('user_id')->toArray()
                ),
            Response::HTTP_FORBIDDEN
        );
        return view('admin.projects.issues.show', compact('project', 'issue'));
    }
}
