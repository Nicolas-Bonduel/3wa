<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

class Users extends Component
{

    public Collection $users;
    public array $edit_role_list = [];

    public array $active_role_edits = [];


    public function toggleEditRole(int $user_id)
    {
        if (($key = array_search($user_id, $this->edit_role_list)) !== false)
            unset($this->edit_role_list[$key]);
        else
            $this->edit_role_list[] = $user_id;
    }

    public function onRoleChange(int $user_id, string $option_value)
    {
        $this->active_role_edits[$user_id] = $option_value;
    }

    public function editRole(int $user_id)
    {
        if (! isset($this->active_role_edits[$user_id]))
            return;
        if (! in_array($this->active_role_edits[$user_id], ['admin', 'user']))
            return;

        $user = User::query()->find($user_id);
        if (! $user) {
            $this->dispatch('admin.refresh-users');
            return;
        }

        $user->update(['role' => $this->active_role_edits[$user_id]]);
        $user->save();
        $this->toggleEditRole($user_id);
    }

    public function logInto(int $user_id)
    {
        $user = User::query()->find($user_id);
        if (! $user) {
            $this->dispatch('admin.refresh-users');
            return;
        }

        auth('user')->login($user);
        $this->js('window.location.reload()');
    }

    public function deleteUser(int $user_id)
    {
        $user = User::query()->find($user_id);
        if (! $user) {
            $this->dispatch('admin.refresh-users');
            return;
        }

        $user->delete();
        $this->dispatch('admin.refresh-users');
    }

    public function addUser()
    {
        $this->dispatch('admin.show-add-user-form');
    }


    #[Layout('layouts.admin')]
    #[On('admin.refresh-users')]
    public function render()
    {
        $this->users = User::all();

        return view('livewire.admin.users');
    }
}
