<?php

use App\Models\User;
use Livewire\Volt\Component;
use Spatie\Permission\Models\Role;
use Illuminate\View\View;

new class extends Component {

    public function rendering(View $view)
    {
        $view->title('Usuario');
    }
    public $name;
    public $last_name;
    public $document;
    public $phone;
    public $email;
    public $image;      // <-- Agregado
    public $image_url;  // <-- Ya lo tienes
    public $password;
    public $password_confirmation;
    public $roles = [];
    public $selectedRoles = [];
    public $user;

    public function mount(User $user)
    {
     $this->user = $user;
        $this->name = $user->name;
        $this->last_name = $user->last_name;
        $this->document = $user->document;
        $this->phone = $user->phone;
        $this->email = $user->email;
        $this->image_url = $user->image_url;
        $this->roles = Role::all();
        $this->selectedRoles = $user->roles->pluck('name')->toArray();
    }


    public function cancel()
    {
        $this->redirect(route('admin.users.index'), navigate: true);
    }
}; ?>

<div>
    
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            [
                'name' => 'Dashboard',
                'route' => route('admin.dashboard'),
            ],
            [
                'name' => 'Usuarios',
                'route' => route('admin.users.index'),
            ],
            [
                'name' => $this->user->name . ' ' . $this->user->last_name,
            ],
        ]" />
    </x-slot>



             
@include('livewire.pages.admin.users.partials.form', ['showPassword' => true,  'editForm' => true,  'showForm' => true,  'mode' => 'show', ])

   

</div>
