<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Users2 extends Component
{

    public Collection $users;


    public function editRole(Request $request)
    {
        $user_id = $request->input('user_id');
        if (! $user_id)
            return response([], 400);
        $user = User::query()->find($user_id);
        if (! $user)
            return response([], 400);
        $role = $request->input('role');
        if (! in_array($role, ['admin', 'user']))
            return response([], 400);

        $user->update(['role' => $role]);
        $user->save();
        return response([], 200);
    }

    public function logInto(Request $request)
    {
        $user_id = $request->input('user_id');
        if (! $user_id)
            return response([], 400);
        $user = User::query()->find($user_id);
        if (! $user)
            return response([], 400);

        auth('user')->login($user);
        return response([], 200);
    }

    public function deleteUser(Request $request)
    {
        $user_id = $request->input('user_id');
        if (! $user_id)
            return response([], 400);
        $user = User::query()->find($user_id);
        if (! $user)
            return response([], 400);

        $user->delete();
        return response([], 200);
    }


    #[Layout('layouts.admin')]
    public function render()
    {
        $this->users = User::all();

        return view('livewire.admin.users2');
    }
}
