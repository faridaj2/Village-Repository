<?php

namespace App\Livewire\User;

use App\Models\User;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Kelola User')]
class Index extends Component
{
    public $users = [];
    public $editingUserId = null;
    public $newRole = '';

    public $showCreateModal = false;
    public $name = '';
    public $email = '';
    public $password = '';
    public $role = 'operator';

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->users = User::all();
    }

    public function openCreateModal()
    {
        $this->reset(['name', 'email', 'password', 'role']);
        $this->role = 'operator';
        $this->showCreateModal = true;
    }

    public function createUser()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
            'role' => 'required|in:super_admin,operator,kadus',
        ]);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'role' => $this->role,
        ]);

        $this->showCreateModal = false;
        $this->reset(['name', 'email', 'password', 'role']);
        $this->loadData();
        session()->flash('message', 'User berhasil ditambahkan.');
    }

    public function editRole($id)
    {
        $user = User::findOrFail($id);
        $this->editingUserId = $user->id;
        $this->newRole = $user->role;
    }

    public function saveRole()
    {
        $this->validate(['newRole' => 'required|in:super_admin,operator,kadus']);

        $user = User::findOrFail($this->editingUserId);

        if ($user->id === auth()->id()) {
            session()->flash('error', 'Tidak bisa mengubah role sendiri.');
            $this->editingUserId = null;
            return;
        }

        $user->update(['role' => $this->newRole]);
        $this->editingUserId = null;
        $this->newRole = '';
        $this->loadData();
        session()->flash('message', 'Role berhasil diperbarui.');
    }

    public function cancelEdit()
    {
        $this->editingUserId = null;
        $this->newRole = '';
    }

    public function render()
    {
        return view('livewire.user.index');
    }
}
