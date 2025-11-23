<?php

namespace App\Http\Livewire;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Models\Concejal;
use Livewire\Attributes\Title;

new #[Title('Registrar Concejal')] class extends Component {
    use WithFileUploads;

    // Propiedades públicas
    public $cedula = '';
    public $nombre = '';
    public $apellido = '';
    public $fecha_nacimiento = '';
    public $telefono = '';
    public $comision_id = '';
    public $cargo = '';
    public $perfil = '';
    public $imagen = null;

    protected function rules()
    {
        return [
            'cedula' => ['required', 'regex:/^[VEJG0-9]{8,10}$/', 'unique:concejal,cedula',],
            'nombre' => 'required|string|min:2|max:255|regex:/^[a-zA-ZÀ-ÿ\s]+$/',
            'apellido' => 'required|string|min:2|max:255|regex:/^[a-zA-ZÀ-ÿ\s]+$/',
            'fecha_nacimiento' => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d')
            .'|after_or_equal:' . now()->subYears(100)->format('Y-m-d'),
            'cargo' => 'required|string|min:5|max:255|regex:/^[a-zA-ZÀ-ÿ\s]+$/',
            'telefono' => 'required|digits_between:10,11|regex:/^(0?4[0-9]{9})$/',
            'perfil' => 'required|string|min:10|max:1000|regex:/^[a-zA-ZÀ-ÿ\s\.\,\;\:\!\?\(\)]+$/',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    protected $messages = [
        'cedula.required' => 'La cédula es obligatoria.',
        'cedula.unique' => 'La cédula ya ha sido registrada.',
        'nombre.required' => 'El nombre es obligatorio.',
        'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
        'nombre.max' => 'El nombre no puede exceder 255 caracteres.',
        'nombre.regex' => 'El nombre solo permite letras y espacios.', 
        'apellido.required' => 'El apellido es obligatorio.',
        'apellido.min' => 'El apellido debe tener al menos 2 caracteres.',
        'apellido.max' => 'El apellido no puede exceder 255 caracteres.',
        'apellido.regex' => 'El apellido solo permite letras y espacios.',     
        'fecha_nacimiento.date' => 'La fecha de nacimiento debe ser válida.',
        'fecha_nacimiento.before_or_equal' => 'La fecha de nacimiento no puede ser mayor a la fecha actual más 18 años.',
        'fecha_nacimiento.after_or_equal' => 'La fecha de nacimiento no puede ser menor a la fecha actual menos 100 años.',
        'telefono.digits' => 'El teléfono debe tener exactamente 11 dígitos.',
        'telefono.regex' => 'El teléfono debe comenzar con un "0" o "4" seguido de 10 a 11 dígitos.',
        'cargo.required' => 'El cargo es obligatorio.',
        'cargo.min' => 'El cargo debe tener al menos 5 caracteres.',
        'cargo.max' => 'El cargo no puede exceder 255 caracteres.',
        'cargo.regex' => 'El cargo solo permite letras y espacios.',
        'perfil.min' => 'El perfil/Descripción debe tener al menos 10 caracteres.',
        'perfil.max' => 'El perfil/Descripción no puede exceder 1000 caracteres.',
        'perfil.regex' => 'El perfil/Descripción solo permite letras, espacios y signos básicos (. , ; : ! ? ( )).',
        'imagen.required' => 'La imagen es obligatoria.',
        'imagen.image' => 'Debe ser un archivo de imagen válido.',
        'imagen.mimes' => 'La imagen debe ser JPEG, PNG, JPG o GIF.',
        'imagen.max' => 'La imagen no puede superar 2MB.',
    ];

    public function updatedImagen()
    {
        if ($this->imagen) {
            $this->validateOnly('imagen');
        }
    }

    public function validateWithAlert()
    {
        try {
            $this->validate();
            return true;
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors();
            $firstField = array_keys($errors->toArray())[0];
            $firstError = $errors->first($firstField);
            $fieldNames = [
                'cedula'=>'Cédula','nombre'=>'Primer Nombre','apellido'=>'Primer Apellido',
                'fecha_nacimiento'=>'Fecha de Nacimiento','telefono'=>'Teléfono',
                'cargo'=>'Cargo','perfil'=>'Perfil','imagen'=>'Imagen'
            ];
            $fieldTitle = $fieldNames[$firstField] ?? 'Campo';
            $this->dispatch('showAlert', [
                'icon'=>'error','title'=>'Error en '.$fieldTitle,'text'=>$firstError,
                'timer'=>8000,'timerProgressBar'=>true,
            ]);
            return false;
        }
    }


    public function save()
    {
        // Normalizar cédula
        if ($this->cedula) {
            $this->cedula = strtoupper($this->cedula);
            if (!str_starts_with($this->cedula, 'V')) {
                $this->cedula = 'V' . $this->cedula;
            }
        }

        if (!$this->validateWithAlert()) return;

        try {
            // Manejar imagen
            $path = null;
            if($this->imagen){
                $originalName = $this->imagen->getClientOriginalName();
                $nameWithoutExtension = pathinfo($originalName, PATHINFO_FILENAME);
                $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                $finalName = $nameWithoutExtension.'_'.time().'.'.$extension;
                $path = $this->imagen->storeAs('concejales', $finalName, 'public');
            }

            // Crear concejal
            Concejal::create([
                'cedula'           => $this->cedula,
                'nombre'           => $this->nombre,       
                'apellido'         => $this->apellido,          
                'fecha_nacimiento' => $this->fecha_nacimiento,
                'telefono' => $this->telefono ? (preg_match('/^(0?4\d{9})$/', $this->telefono) ? '+58'
                . (strlen($this->telefono) === 10 ? '0' . $this->telefono : ltrim($this->telefono, '0')) : null) : null,
                'cargo'            => $this->cargo,
                'perfil'           => $this->perfil,
                'imagen_url'       => $path,
            ]);

            $this->limpiar(false);

            $this->dispatch('showAlert', [
                'icon'=>'success',
                'title'=>'Éxito',
                'text'=>'Concejal registrado con éxito.',
                'timer'=>2000,
                'timerProgressBar'=>true,
            ]);

            return $this->redirect(route('admin.concejales.index'), navigate: true);

        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon'=>'error',
                'title'=>'Error',
                'text'=>'Ocurrió un error: '.$e->getMessage(),
                'timer'=>3000,
                'timerProgressBar'=>true,
            ]);
        }
    }
    public function cancel()
    {
        return $this->redirect(route('admin.concejales.index'), navigate: true);
    }

    public function limpiar($showAlert=true)
    {
        $this->reset([
            'cedula','nombre','apellido',
            'fecha_nacimiento','telefono','cargo','perfil','imagen'
        ]);
        $this->resetValidation();
        if($showAlert) {
            $this->dispatch('showAlert', [
                'icon'=>'info','title'=>'Formulario limpiado',
                'text'=>'Todos los campos han sido reiniciados.',
                'timer'=>2000,'timerProgressBar'=>true,
                'toast'=>true,'position'=>'top-end','showConfirmButton'=>false,
            ]);
        }
    }


};
?>
<div>
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            [
                'name' => 'Dashboard',
                'route' => route('admin.dashboard'),
            ],
            [
                'name' => ' Registrar Concejal',
            ],
        ]" />
    </x-slot>
@include('livewire.pages.admin.concejales.form.form', ['mode' => 'create'])
</div>
