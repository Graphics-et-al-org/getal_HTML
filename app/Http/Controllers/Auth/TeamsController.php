<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamsController extends Controller
{
    /**
     * Display the backend index.
     */
    public function index(): View
    {
        $teams = Team::paginate(10);;
        return view('backend.teams.index')
            ->with('teams', $teams);
    }

    public function new(): View
    {
        $teams = Team::all();
        return view('backend.teams.new')
            ->with('teams', $teams);
    }

    public function create(Request $request): RedirectResponse
    {
        //   dd($request->get('roles'));
        $checkteams = Team::where('name', $request->name)->first();
        if ($checkteams) {
            session()->flash('flash_danger', "Team name already used");
            return redirect()->route('admin.teams.new')->withInput();
        }
        Team::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description,
        ]);

        session()->flash('flash_success', "New team created");
        return redirect()->route('admin.teams.index');
    }


    public function edit(Request $request): View
    {
        $roles = Role::all();
        $teams = Team::all();
        $user = User::find($request->id);
        return view('backend.teams.edit')
            ->with('user', $user)->with('roles', $roles)
            ->with('teams', $teams);
    }


    public function update(Request $request): RedirectResponse
    {

        // $user = User::find($request->get('id'));
        // if (isset($user->provider_id)) {
        //     $user->roles()->sync($request->get('roles'), $request->get('teams'));
        // } else {
        //     $user->update([
        //         'name' =>$request->get('name'),
        //         'email' => $request->get('email'),

        //     ]);
        //     if ($request['password'] && strlen($request->get('password')) > 1) {
        //         $user->update([
        //             'password' => Hash::make($request->get('password')),
        //         ]);
        //     }
        //     $user->roles()->sync($request->get('roles'), $request->get('teams'));

        // }
        // $user->update($request->all());
        return redirect()->route('admin.teams.index');
    }

    public final function destroy($id): RedirectResponse
    {
        $team = Team::find($id);
        $team->delete();
        return redirect()->route('admin.teams.index');
    }

    public function showmembership($id): View
    {
        $team = Team::find($id);
        $roles = Role::all();
        $members = $team->users()->distinct()->paginate(10);
        return view('backend.teams.members')
            ->with('team', $team)
            ->with('roles', $roles)
            ->with('members', $members);
    }

    public function addmembers(Request $request): RedirectResponse
    {
        $team = Team::find($request->team_id);
        $users = User::whereIn('id', $request->members)->get();
        $roles = Role::whereIn('id', $request->roles)->get()->toArray();
        // dd($roles);
        // $defaultRole = Role::where('default', 'true')->first();
        foreach ($users as $user) {
            $user->syncRoles($roles, $team);
        }
        return redirect()->route('admin.teams.membership', $request->get('team_id'));
    }

    public function removemembers($id, $userid): RedirectResponse
    {
        $user = User::find($userid);
        $roles = $user->roles()->wherePivot('team_id', $id)->get()->toArray();
        $team = Team::find($id);
        $user->removeRoles($roles, $team);
        return redirect()->route('admin.teams.membership', $id);
    }
}
