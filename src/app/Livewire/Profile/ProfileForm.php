<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class ProfileForm extends Component
{
    public $name, $email, $phone, $current_password, $new_password;

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
    }

    public function updateProfile()
    {
        $this->validate(['name' => 'required', 'email' => 'required|email|unique:users,email,' . auth()->id()]);
        auth()->user()->update(['name' => $this->name, 'email' => $this->email, 'phone' => $this->phone]);
        session()->flash('message', 'Profil diupdate');
    }

    public function updatePassword()
    {
        $this->validate(['current_password' => 'required|current_password', 'new_password' => 'required|min:8']);
        auth()->user()->update(['password' => Hash::make($this->new_password)]);
        session()->flash('message', 'Password diubah');
    }

    public function render()
    {
        return view('livewire.profile.profile-form')->layout('components.layouts.livewire');
    }
}