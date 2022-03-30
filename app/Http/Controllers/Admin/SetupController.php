<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Label;
use App\Models\Priority;
use App\Models\Role;
use App\Models\Status;
use Illuminate\Http\Request;

class SetupController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        $labels = Label::all();
        $priorities = Priority::all();
        $statuses = Status::all();

        return view('admin.setup', compact('roles', 'labels', 'priorities', 'statuses'));
    }
}
