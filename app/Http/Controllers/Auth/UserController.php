<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Organisation\Project;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display the backend index.
     */
    public function backend_index(): View
    {
        $users = User::paginate(10);;
        return view('backend.users.index')
            ->with('users', $users);
    }

    public function backend_new(): View
    {
        $roles = Role::all();
        $teams = Team::all();
        $projects = Project::all();
        return view('backend.users.new')
            ->with('roles', $roles)
            ->with('teams', $teams)
            ->with('projects', $projects);;
    }

    // Create a new user from teh admin panel
    public function backend_create(Request $request): RedirectResponse
    {
        //   dd($request->get('roles'));
        $checkuser = User::where('email', $request->email)->first();
        if ($checkuser) {
            session()->flash('flash_danger', "User already exists");
            return redirect()->route('admin.users.new')->withInput();
        }

        if ($request->type_radio == 'local') {
            if ($request['password'] && strlen($request->password) > 8) {
                $user = User::create($request->all());
                $user->update([
                    'password' => Hash::make($request->password),
                ]);
            } else {
                session()->flash('flash_danger', "Need a password, 8 characters or more");
                return redirect()->route('admin.users.new')->withInput();
            }
        }
        if ($request->type_radio == 'provider') {
            $user = User::create($request->all());
        }

        $user->roles()->sync($request->get('roles'), $request->get('teams'));
        $user->projects()->sync($request->get('projects'));

        session()->flash('flash_success', "New user created");
        return redirect()->route('admin.users.index');
    }


    // show the update user form
    public function backend_edit(Request $request): View
    {
        $roles = Role::all();
        $teams = Team::all();
        $projects = Project::all();
        $user = User::find($request->id);
       // dd($user->roles);
        return view('backend.users.edit')
            ->with('user', $user)->with('roles', $roles)
            ->with('teams', $teams)
            ->with('projects', $projects);
    }

// update the user
    public function backend_update(Request $request, $id): RedirectResponse
    {

        $user = User::find($id);
        if (isset($user->provider_id)) {
            $user->roles()->sync($request->get('roles'), $request->get('teams'));
        } else {
            $user->update([
                'name' => $request->get('name'),
                'email' => $request->get('email'),

            ]);
            if ($request['password'] && strlen($request->get('password')) > 1) {
                $user->update([
                    'password' => Hash::make($request->get('password')),
                ]);
            }
            $user->roles()->sync($request->get('roles'), $request->get('teams'));
            $user->projects()->sync($request->get('projects'));
        }
        $user->update($request->all());
        return redirect()->route('admin.users.index');
    }

    // delete the user
    public function backend_destroy(User $user): RedirectResponse
    {
        $user->delete();
        return redirect()->route('admin.users.index');
    }

    // check that an email (does not) exists
    public function checkEmail(Request $request)
    {
        $checkuser = User::where('email', $request->get('email'))->first();
        if ($checkuser) {
            return response()->json(['status' => 'error', 'message' => 'User already exists']);
        } else {
            return response()->json(['status' => 'success', 'message' => 'User does not exist']);
        }
    }

    // Search for a user by name or email
    public function searchusers(Request $request)
    {
        if ($request->q) {
            $users = User::where('name', 'like', '%' . $request->q . '%')
                ->orWhere('email', 'like', '%' . $request->q . '%')->take(20)->get();
        } else {
            $users = User::take(50)->get();
        }
        $users->transform(function ($item, $key) {
            return ['value' => $item->id, 'text' => $item->name . ':' . $item->email];
        });
        return response()->json($users);
    }

    // impersonate a user
    //@TODO log this
    public function backend_impersonate(Request $request, $id): RedirectResponse
    {
        $user = User::find($id);
        if ($user) {
            Auth::user()->impersonate($user);
            session()->flash('flash_success', "Impersonating user " . $user->name);
            return redirect()->route('admin.users.index');
        } else {
            session()->flash('flash_danger', "User not found");
            return redirect()->route('admin.users.index');
        }
    }

    // leave impersonation
    public function backend_leave_impersonate(): RedirectResponse
    {
        Auth::user()->leaveImpersonation();
        session()->flash('flash_success', "Left impersonation");
        return redirect()->route('admin.users.index');
    }

}
