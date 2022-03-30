<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use App\Enums\StatusEnum;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{

    public function index(): View
    {
        $users = User::query()
            ->with('role')
            ->when(request('keyword'), function ($q) {
                $q->where('name', 'like', '%' . request('keyword') . '%');
            })
            ->when(request('role_id'), function ($q) {
                $q->where('role_id', '=',  request('role_id'));
            })
            ->where('id', '!=', auth()->user()->id)
            ->orderByDesc('last_seen')
            ->get();
        $roles = Role::all();
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function profile()
    {
        $user = User::with('role')
            ->withCount(['issues' => function ($query) {
                $query->where('status_id', '!=', StatusEnum::CANCELED->value);
            }])
            ->find(auth()->user()->id);

        $projectsCount = $user->issues()->selectRaw('count(distinct project_id) as projects_count')
            ->where('status_id', '!=', StatusEnum::CANCELED->value)
            ->first()->projects_count;

        return view('admin.users.profile', compact('user', 'projectsCount'));
    }

    public function updateProfile()
    {
        $data = validator(request()->all(), [
            'name' => ['required'],
            'email' => ['email', Rule::unique('users', 'email')->whereNot('id', auth()->user()->id)],
            'phone' => ['required'],
            'address' => ['nullable', 'string'],
            'job_title' => ['nullable'],
            'password' => [
                Password::min(8)->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
                'confirmed',
                'nullable'
            ]
        ])->validate();
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }
        $user = User::find(auth()->user()->id);
        $user->update($data);
        return redirect()->route('admin.profile.edit')->with('message', __('Successfully updated.'));
    }

    public function create()
    {
        $user = new User();
        $roles = Role::all();
        return view('admin.users.create', compact('user', 'roles'));
    }


    public function store(CreateUserRequest $request)
    {
        $data = $request->validated();
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }
        User::insert($data);
        return redirect()->route('admin.users.index')->with('message', __('Successfully created.'));
    }


    public function show($id)
    {
        //
    }


    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.create', compact('user', 'roles'));
    }


    public function update(CreateUserRequest $request, User $user)
    {
        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }

        $user->fill($data);
        $user->save();
        return redirect()->route('admin.users.index')->with('message', __('Successfully updated.'));
    }


    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('message', __('Successfully deleted.'));
    }
}
