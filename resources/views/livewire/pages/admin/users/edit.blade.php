<?php

use App\Models\User;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

new class extends Component {

    use WithFileUploads;

    public $name = '';
    public $last_name = '';
    public $document_type = 'V';
    public $document_number = '';
    public $phone = '';
    public $email = '';
    public $image;
    public $image_url = '';
    public $password = '';
    public $password_confirmation = '';
    public $roles = [];
    public $selectedRole = '';
    public $user;
    public $mode = 'edit'; // modo editar

    public function rendering(View $view)
    {
        $view->title('Editar Usuario');
    }

    public function mount(User $user)
    {
        $this->user = $user;

        // Cargar datos
        $this->name = $user->name;
        $this->last_name = $user->last_name;
        $this->phone = $user->phone ? preg_replace('/^\+58/', '', $user->phone) : '';

        // Documento: separar letra y número
        if (preg_match('/^(V|E)(\d{8})$/', $user->document, $matches)) {
            $this->document_type = $matches[1];
            $this->document_number = $matches[2];
        }

        $this->email = $user->email;
        $this->image_url = $user->image_url;
        $this->roles = Role::all();
        $this->selectedRole = $user->roles->pluck('name')->first();
    }

    protected function rules()
    {
        return [
            'name' => ['required','string','max:255','regex:/^[\pL\s]+$/u'],
            'last_name' => ['required','string','max:255','regex:/^[\pL\s]+$/u'],
            'document_number' => ['required','digits:8', Rule::unique('users','document')->ignore($this->user->id)],
            'phone' => ['required','regex:/^(0?4\d{9})$/'],
            'email' => ['required','email', Rule::unique('users','email')->ignore($this->user->id)],
            'password' => $this->password ? 'min:8|confirmed' : '',
            'selectedRole' => 'required|string|exists:roles,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        // Imagen
        if ($this->image) {
            if ($this->image_url && Storage::disk('public')->exists($this->image_url)) {
                Storage::disk('public')->delete($this->image_url);
            }
            $this->image_url = $this->image->storeAs('images/users', $this->image->getClientOriginalName(), 'public');
        }

        // Formatear teléfono +58
        $telefonoFormateado = '+58' . ltrim($this->phone, '0');

        // Formatear documento
        $documentoFormateado = $this->document_type . $this->document_number;

        $this->user->update([
            'name' => $this->name,
            'last_name' => $this->last_name,
            'document' => $documentoFormateado,
            'phone' => $telefonoFormateado,
            'email' => $this->email,
            'image_url' => $this->image_url,
        ]);

        if ($this->password) {
            $this->user->update(['password' => Hash::make($this->password)]);
        }

        $this->user->syncRoles([$this->selectedRole]);

        $this->dispatch('showAlert', [
            'icon'=>'success',
            'title'=>'¡Usuario actualizado!',
            'text'=>'El usuario se ha actualizado correctamente.',
            'timer'=>2000,
            'timerProgressBar'=>true,
        ]);

        return $this->redirect(route('admin.users.index'), navigate: true);
    }

    public function cancel()
    {
        return $this->redirect(route('admin.users.index'), navigate: true);
    }
};
?>


<div>
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['name' => 'Usuarios', 'route' => route('admin.users.index')],
            ['name' => 'Editar usuario'],
        ]" />
    </x-slot>


        @include('livewire.pages.admin.users.partials.form', [ 'showForm' => true, 'editForm' => true, 'mode' => 'edit' ])

</div>