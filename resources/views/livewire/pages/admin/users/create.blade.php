<?php

use App\Models\User;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

new class extends Component
{
    use WithFileUploads;

    public $name = '';
    public $last_name = '';
    public $document_type = 'V'; // V o E
    public $document_number = '';
    public $phone = '';
    public $email = '';
    public $image;
    public $image_url = '';
    public $password = '';
    public $password_confirmation = '';
    public $roles = [];
    public $selectedRole = '';

    public function rendering(View $view)
    {
        $view->title('Crear Usuario');
    }

    public function mount()
    {
        $this->roles = Role::all();
    }

    protected function rules()
    {
        return [
            'name' => ['required','string','max:255','regex:/^[\pL\s]+$/u'],
            'last_name' => ['required','string','max:255','regex:/^[\pL\s]+$/u'],
            'document_number' => 'required|digits:8|unique:users,document',
            'phone' => ['required','regex:/^(0?4\d{9})$/'],
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'selectedRole' => 'required|string|exists:roles,name',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
        ];
    }

    protected $messages = [
        'name.required' => 'El nombre es obligatorio.',
        'name.regex' => 'El nombre solo puede contener letras y espacios.',
        'last_name.required' => 'El apellido es obligatorio.',
        'last_name.regex' => 'El apellido solo puede contener letras y espacios.',
        'document_number.required' => 'La cédula es obligatoria.',
        'document_number.digits' => 'La cédula debe tener 8 dígitos.',
        'document_number.unique' => 'Cédula ya registrada.',
        'phone.required' => 'El teléfono es obligatorio.',
        'phone.regex' => 'El teléfono debe comenzar con 0 o 4 y ser un número venezolano válido.',
        'email.required' => 'El correo es obligatorio.',
        'email.email' => 'Debe ingresar un correo válido.',
        'email.unique' => 'Este correo ya está registrado.',
        'password.required' => 'La contraseña es obligatoria.',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        'password.confirmed' => 'Las contraseñas no coinciden.',
        'selectedRole.required' => 'Debe seleccionar un rol.',
        'image.required' => 'La imagen es obligatoria.',
        'image.image' => 'El archivo debe ser una imagen.',
        'image.mimes' => 'La imagen debe ser jpeg, png, jpg, gif o svg.',
        'image.max' => 'La imagen no debe superar los 20 MB.',
    ];

    public function save()
    {
        try {
            $validated = $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = collect($e->validator->errors()->all());
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Errores de validación',
                'html' => '<ul class="text-left list-disc ml-5 text-2xl space-y-2">' 
                    . $errors->map(fn($msg) => "<li>{$msg}</li>")->implode('') 
                    . '</ul>',
                'width' => '800px',
                'padding' => '2em',
                'customClass' => ['title' => 'text-3xl font-bold','htmlContainer' => 'text-lg'],
                'confirmButtonText' => 'OK',
            ]);
            return;
        }

        // Guardar imagen con nombre original
        if ($this->image) {
            $this->image_url = $this->image->storeAs('images/users', $this->image->getClientOriginalName(), 'public');
        }

        // Formatear teléfono: +58 + quitar 0 inicial
        $telefonoFormateado = '+58' . ltrim($this->phone, '0');

        // Formatear documento: letra + 8 dígitos
        $documentoFormateado = $this->document_type . $this->document_number;

        // Crear usuario
        $user = User::create([
            'name'       => $this->name,
            'last_name'  => $this->last_name,
            'document'   => $documentoFormateado,
            'phone'      => $telefonoFormateado,
            'email'      => $this->email,
            'image_url'  => $this->image_url ?: null,
            'password'   => Hash::make($this->password),
        ]);

        $user->assignRole($this->selectedRole);

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Usuario creado!',
            'text' => 'El usuario se ha registrado correctamente.',
            'width' => '500px',
            'padding' => '2em',
            'confirmButtonText' => 'Genial',
        ]);

        return $this->redirect(route('admin.users.index'), navigate: true);
    }

    // Función limpiar campos con alerta opcional
public function limpiar($withAlert = true)
{
    // Limpiar todos los campos
    $this->name = '';
    $this->last_name = '';
    $this->document_type = 'V';
    $this->document_number = '';
    $this->phone = '';
    $this->email = '';
    $this->image = null;
    $this->image_url = '';
    $this->password = '';
    $this->password_confirmation = '';
    $this->selectedRole = '';

    // Disparar alerta si $withAlert es true
    if ($withAlert) {
      $this->dispatch('showAlert', [
            'icon' => 'info',
            'title' => 'Formulario limpio',
            'text' => 'Se han borrado todos los campos del formulario.',
            'timer' => 2000,
            'timerProgressBar' => true,
        ]);
    }
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
            ['name' => 'Crear Usuario'],
        ]" />
    </x-slot>


@include('livewire.pages.admin.users.partials.form', ['showForm' => true, 'editForm' => false, 'mode' => 'create' ])
   
</div>