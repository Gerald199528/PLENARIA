<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Models\Concejal;
use App\Models\Comision;
use Livewire\Attributes\Title;

new #[Title('Editar Concejal')] class extends Component
{
    use WithFileUploads;

    public Concejal $concejal;

    public $cedula;
    public $nombre; 
    public $apellido;
    public $fecha_nacimiento;
    public $telefono;
    public $comision_id;
    public $cargo;
    public $perfil;
    public $imagen = null;
    public $nueva_comision;
    public $descripcion_comision;
     public $miembro;

    // Propiedad para guardar la lista de comisiones
    public $comisiones;

    public function mount(Concejal $concejal)
    {
        $this->concejal = $concejal;

        // Cargar la lista de comisiones
        $this->comisiones = Comision::all();
// Inicializar miembro si existe
$this->miembro = DB::table('comision_concejal')
    ->where('concejal_id', $concejal->id)
    ->first(); // obtenemos todo el registro, no solo id

        // Inicializar campos con valores existentes
        $this->cedula = str_replace('V', '', $concejal->cedula);
        $this->nombre = $concejal->nombre;
        $this->segundo_nombre = $concejal->segundo_nombre;
        $this->apellido = $concejal->apellido;
        $this->segundo_apellido = $concejal->segundo_apellido;
        $this->fecha_nacimiento = $concejal->fecha_nacimiento;
        $this->telefono = $concejal->telefono ? str_replace('+58','',$concejal->telefono) : null;
        
        // Asignar el ID de la comisión
        $this->comision_id = $concejal->comisions->pluck('id')->first() ?? '';

        $this->cargo = $concejal->cargo;
        $this->perfil = $concejal->perfil;
    }

    protected function rules()
    {
        return [
            'cedula' => [
                'required',
                'digits:8',
                function ($attribute, $value, $fail) {
                    $cedulaOriginal = str_replace('V', '', $this->concejal->cedula);
                    if ($value !== $cedulaOriginal) {
                        $cedulaCompleta = 'V' . $value;
                        $existe = Concejal::where('cedula', $cedulaCompleta)
                            ->where('id', '!=', $this->concejal->id)
                            ->exists();
                        if ($existe) {
                            $fail('La cédula ya ha sido registrada por otro concejal.');
                        }
                    }
                },
            ],
            'nombre' => 'required|string|min:2|max:255|regex:/^[a-zA-ZÀ-ÿ\s]+$/',           
            'apellido' => 'required|string|min:2|max:255|regex:/^[a-zA-ZÀ-ÿ\s]+$/',          
            'fecha_nacimiento' => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d')
            . '|after_or_equal:' . now()->subYears(100)->format('Y-m-d'),
            'telefono' => 'required|digits_between:10,11|regex:/^(0?4[0-9]{9})$/',
             'comision_id' => 'nullable|exists:comisions,id',
            'cargo' => 'required|string|min:5|max:255|regex:/^[a-zA-ZÀ-ÿ\s]+$/',
            'perfil' => 'required|string|min:10|max:1000|regex:/^[a-zA-ZÀ-ÿ\s\.\,\;\:\!\?\(\)]+$/',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    protected $messages = [
        'cedula.digits' => 'La cédula debe tener exactamente 8 números.', 
        'nombre.min' => 'El primer nombre debe tener al menos 2 caracteres.',
        'nombre.max' => 'El primer nombre no puede exceder 255 caracteres.',
        'nombre.regex' => 'El primer nombre solo permite letras y espacios.',    
        'apellido.min' => 'El primer apellido debe tener al menos 2 caracteres.',
        'apellido.max' => 'El primer apellido no puede exceder 255 caracteres.',
        'apellido.regex' => 'El primer apellido solo permite letras y espacios.',    
        'fecha_nacimiento.date' => 'La fecha de nacimiento debe ser válida.',
        'fecha_nacimiento.before_or_equal' => 'La fecha de nacimiento no puede ser mayor a la fecha actual menos 18 años.',
        'fecha_nacimiento.after_or_equal' => 'La fecha de nacimiento no puede ser menor a la fecha actual menos 100 años.',
       'telefono.digits' => 'El teléfono debe tener exactamente 11 dígitos.',
        'telefono.regex' => 'El teléfono debe comenzar con un "0" o "4" seguido de 10 a 11 dígitos (ej: 4123456789).',
        'comision_id.required' => 'La comisión es obligatoria.',
        'comision_id.exists' => 'La comisión seleccionada no es válida.', 
        'cargo.min' => 'El cargo debe tener al menos 5 caracteres.',
        'cargo.max' => 'El cargo no puede exceder 255 caracteres.',
        'cargo.regex' => 'El cargo solo permite letras y espacios.',
        'perfil.min' => 'El perfil debe tener al menos 10 caracteres.',
        'perfil.max' => 'El perfil no puede exceder 1000 caracteres.',
        'perfil.regex' => 'El perfil solo permite letras, espacios y signos básicos (. , ; : ! ? ( )).',
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
                'comision_id'=>'Comisión','cargo'=>'Cargo','perfil'=>'Perfil','imagen'=>'Imagen'
            ];
            $fieldTitle = $fieldNames[$firstField] ?? 'Campo';
            $this->dispatch('showAlert', [
                'icon'=>'error','title'=>'Error en '.$fieldTitle,'text'=>$firstError,
                'timer'=>8000,'timerProgressBar'=>true,
            ]);
            return false;
        }
    }

public function update()
{
    if (!$this->validateWithAlert()) return;

    try {
        $path = $this->concejal->imagen_url;

        // Manejo de la imagen
        if ($this->imagen) {
            $originalName = $this->imagen->getClientOriginalName();
            $nameWithoutExtension = pathinfo($originalName, PATHINFO_FILENAME);
            $extension = pathinfo($originalName, PATHINFO_EXTENSION);
            $finalName = $nameWithoutExtension.'_'.time().'.'.$extension;
            $path = $this->imagen->storeAs('concejales', $finalName, 'public');
        }

        // Manejar la comisión: nueva o existente
        if ($this->nueva_comision) {
            $comision = Comision::create([
                'nombre' => $this->nueva_comision,
                'descripcion' => $this->descripcion_comision ?: null,
            ]);
        } else {
            $comision = $this->comision_id ? Comision::find($this->comision_id) : null;
        }

        $comisionNombre = $comision ? $comision->nombre : null;

        // Actualizamos los datos del concejal
        $this->concejal->update([
            'cedula' => 'V' . $this->cedula,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'telefono' => $this->telefono ? (preg_match('/^(0?4\d{9})$/', $this->telefono) ? '+58'
                . (strlen($this->telefono) === 10 ? '0' . $this->telefono : ltrim($this->telefono, '0')) : null) : null,
            'comision' => $comisionNombre,
            'cargo' => $this->cargo,
            'perfil' => $this->perfil,
            'imagen_url' => $path,
        ]);

        // Inicializar miembro si existe
        $this->miembro = DB::table('comision_concejal')
            ->where('concejal_id', $this->concejal->id)
            ->first();

        // Actualizar o insertar relación con la comisión
        if ($comision) {
            DB::table('comision_concejal')->updateOrInsert(
                ['concejal_id' => $this->concejal->id],
                [
                    'comision_id' => $comision->id,
                    'miembro_id' => $this->miembro ? $this->miembro->miembro_id : null,
                    'nombre_concejal' => $this->concejal->nombre . ' ' . $this->concejal->apellido,
                    'cedula_concejal' => 'V' . $this->cedula,
                    'nombre_comision' => $comisionNombre,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        $this->dispatch('showAlert', [
            'icon' => 'success',
            'title' => 'Éxito',
            'text' => 'Concejal actualizado con éxito.',
            'timer' => 2000,
            'timerProgressBar' => true,
        ]);

        $this->dispatch('redirectAfterSave');
        return $this->redirect(route('admin.concejales.index'), navigate: true);

    } catch (\Exception $e) {
        $this->dispatch('showAlert', [
            'icon' => 'error',
            'title' => 'Error',
            'text' => 'Ocurrió un error: ' . $e->getMessage(),
            'timer' => 3000,
            'timerProgressBar' => true,
        ]);
    }
}


    public function cancel()
    {
        return $this->redirect(route('admin.concejales.index'), navigate: true);
    }
};




 ?>

<div>
   <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['name' => 'Concejales', 'route' => route('admin.concejales.index')],
            ['name' => 'Editar concejal'],
        ]" />
    </x-slot>


@include('livewire.pages.admin.concejales.form.form', [
    'mode' => 'edit'
])

</div>
