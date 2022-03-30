<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $onlineUsers = User::query()->with(['role'])->online()->get();

        $projects = Project::conditions()->orderByDesc('id')->paginate(5);

        $projectsBudgetByMonthYear = Project::query()
            ->selectRaw("monthname(due_date) as month, year(due_date) as year, sum(budget) as profit")
            ->whereRaw("due_date < now()")
            ->groupByRaw("monthname(due_date), year(due_date)")
            ->get();
        //dd($projectsBudgetByMonthYear->unique('year')->values()->pluck('year'));
        // dd($projectsBudgetByMonthYear->unique(function ($item) {
        //     return $item->year . $item->month;
        // })->values()->pluck('profit'));
        // dd($projectsBudgetByMonthYear->pluck('profit'));
        // dd($projectsBudgetByMonthYear->unique('year')->values()->pluck('year'));
        // dd($projectsBudgetByMonthYear->unique('month')->pluck('month'));

        return view('dashboard', compact('onlineUsers', 'projects', 'projectsBudgetByMonthYear'));
    }
}
