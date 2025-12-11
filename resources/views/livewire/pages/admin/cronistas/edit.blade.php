<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Models\Cronista;
use App\Models\Estado;
use App\Models\Municipio;
use App\Models\Parroquia;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL; 

new #[Title('Editar Cronista')] class extends Component
{
    use WithFileUploads;

    public Cronista $cronista;
    public $nombre = '';
    public $apellido = '';
    public $cedula = '';
    public $telefono = '';
    public $cargo = '';
    public $perfil = '';
    public $imagen = null;
    public $estado_id = null;
    public $municipio_id = null;
    public $parroquia_id = null;
    public $email = '';
    public $fecha_ingreso = '';
    public $imagen_url_actual = null; 
    public $returnUrl = ''; 
    public $estados = [];
    public $municipios = [];
    public $parroquias = [];
    public $mode = 'edit';

    public function mount(Cronista $cronista)
    {
        $this->cronista = $cronista;
        $this->estados = Estado::all();

        
        // --- Lógica de Redirección Inteligente ---
        $prevUrl = URL::previous();
        $currentEditUrl = route('admin.cronistas.edit', $cronista); 
        if (str_contains($prevUrl, $currentEditUrl)) {          
            $this->returnUrl = route('admin.cronistas.show', $cronista);
        } else {          
            $this->returnUrl = $prevUrl;
        }
        
        // ------------------------------------------
        // Cargar datos existentes
        $this->nombre = $cronista->nombre_completo;
        $this->apellido = $cronista->apellido_completo;
        $this->cedula = preg_replace('/V/', '', $cronista->cedula);
        $this->telefono = $cronista->telefono ? preg_replace('/^\+58(\s)?/', '', $cronista->telefono) : null;
        $this->cargo = $cronista->cargo;
        $this->perfil = $cronista->perfil;
        $this->imagen_url_actual = $cronista->imagen_url;
        $this->email = $cronista->email;
        $this->fecha_ingreso = $cronista->fecha_ingreso ? $cronista->fecha_ingreso->format('Y-m-d') : '';

        // Ubicación
        if ($cronista->parroquia_id) {
            $this->parroquia_id = $cronista->parroquia_id;
            $this->municipio_id = $cronista->parroquia->municipio_id;
            $this->estado_id = $cronista->parroquia->municipio->estado_id;

            $this->municipios = Municipio::where('estado_id', $this->estado_id)->get();
            $this->parroquias = Parroquia::where('municipio_id', $this->municipio_id)->get();
        }
    }

    public function updatedEstadoId($estadoId)
    {
        $this->municipios = Municipio::where('estado_id', $estadoId)->get();
        $this->municipio_id = null;
        $this->parroquias = [];
        $this->parroquia_id = null;
    }

    public function updatedMunicipioId($municipioId)
    {
        $this->parroquias = Parroquia::where('municipio_id', $municipioId)->get();
        $this->parroquia_id = null;
    }

    protected function rules()
    {
        $cedulaUnique = $this->cronista ? 'unique:cronistas,cedula,' . $this->cronista->id : 'unique:cronistas,cedula';
        $uniqueEmailRule = 'required|email|max:255|unique:cronistas,email,' . $this->cronista->id;
        
        return [
                'imagen' => $this->imagen && is_object($this->imagen) ? 'image|mimes:jpeg,png,jpg,gif|max:2048' : 'nullable',
                'cedula' => 'required|digits:8|unique:concejal,cedula',
                'nombre' => 'required|string|min:2|max:255|regex:/^[a-zA-ZÀ-ÿ\s]+$/',     
                'apellido' => 'required|string|min:2|max:255|regex:/^[a-zA-ZÀ-ÿ\s]+$/',       
                'telefono'   => 'required|digits_between:10,11|regex:/^(0?4[0-9]{9})$/',
                'cargo' => 'required|string|min:3|max:255|regex:/^[a-zA-ZÀ-ÿ\s]+$/',
                'email' => 'required|email|max:255|unique:cronistas,email' . ($this->cronista ? ',' . $this->cronista->id : ''),  
                'fecha_ingreso' => 'required|date',      
                'estado_id' => 'required|exists:estados,id',
                'municipio_id' => 'required|exists:municipios,id',
                'parroquia_id' => 'required|exists:parroquias,id',
                'perfil' => 'required|string|min:10|max:1000',         
        ];
    }
    protected $messages = [         
        'imagen.max' => 'La imagen no puede superar 2MB.',
        'imagen.required' => 'La imagen es obligatoria.',
        'imagen.image' => 'Debe ser un archivo de imagen válido.',
        'imagen.mimes' => 'La imagen debe ser JPEG, PNG, JPG o GIF.',  
        'cedula.required' => 'La cédula es obligatoria.',
        'cedula.digits' => 'La cédula debe tener 8 números.',
        'cedula.unique' => 'La cédula ya está registrada.',
        'nombre.required' => 'El nombre es obligatorio.',
        'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
        'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
        'apellido.required' => 'El apellido es obligatorio.',
        'apellido.min' => 'El apellido debe tener al menos 2 caracteres.',
        'apellido.regex' => 'El apellido solo puede contener letras y espacios.',
        'telefono.digits' => 'El teléfono debe tener exactamente 11 dígitos.',
        'telefono.regex' => 'El teléfono debe comenzar con un "0" o "4" seguido de 10 a 11 dígitos (ej: 04129765725).',
        'cargo.required' => 'El cargo es obligatorio.',
        'cargo.min' => 'El cargo debe tener al menos 2 caracteres.',      
        'email.email' => 'El email debe ser válido.',
        'email.unique' => 'El email ya está registrado.',     
        'fecha_ingreso.date' => 'La fecha de ingreso debe ser una fecha válida.',
        'estado_id.required' => 'El estado es obligatorio.',
        'municipio_id.required' => 'El municipio es obligatorio.',
        'parroquia_id.required' => 'La parroquia es obligatoria.',
        'perfil.required' => 'El perfil es obligatorio, no se permite numeros ni caracteres especiales.',   
    ];

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
                'cedula'=>'Cédula','nombre'=>'Nombre Completo','apellido'=>'Apellido Completo',
                'telefono'=>'Teléfono','cargo'=>'Cargo','perfil'=>'Perfil','imagen'=>'Imagen',
                'estado_id'=>'Estado','municipio_id'=>'Municipio','parroquia_id'=>'Parroquia','email'=>'Email',
                'fecha_ingreso'=>'Fecha de Ingreso',
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
            // Lógica de Imagen (Eliminación y guardado)
            $path = $this->imagen_url_actual;
            if ($this->imagen && is_object($this->imagen)) {
                if ($this->imagen_url_actual) {
                    Storage::disk('public')->delete($this->imagen_url_actual);
                }
                $originalName = $this->imagen->getClientOriginalName();
                $nameWithoutExtension = pathinfo($originalName, PATHINFO_FILENAME);
                $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                $finalName = $nameWithoutExtension.'_'.time().'.'.$extension;
                $path = $this->imagen->storeAs('cronistas_imagen', $finalName, 'public');
            }

                $this->cronista->update([
                'cedula' => $this->cedula ? 'V'.$this->cedula : null,
                'nombre_completo' => $this->nombre,
                'apellido_completo' => $this->apellido,
                'telefono'  => $this->telefono 
                ? (preg_match('/^(0?4\d{9})$/', $this->telefono) 
                ? '+58' . ltrim($this->telefono, '0') 
                : null) 
                : null,
                'perfil' => $this->perfil,
                'imagen_url' => $path,
                'parroquia_id' => $this->parroquia_id, 
                'email' => $this->email,
                'fecha_ingreso' => $this->fecha_ingreso,
            ]);

            $this->dispatch('showAlert', [
                'icon'=>'success',
                'title'=>'Éxito',
                'text'=>'Cronista actualizado correctamente.',
                'timer'=>2000,
                'timerProgressBar'=>true,
            ]);

        
            return $this->redirect($this->returnUrl, navigate: true);

        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon'=>'error',
                'title'=>'Error',
                'text'=>'Ocurrió un error: '.$e->getMessage(),
                'timer'=>5000,
                'timerProgressBar'=>true,
            ]);
        } 
    }
    
    public function cancel()
    {
        // Redirige a la URL de origen, ya sea Show o Index.
        return $this->redirect($this->returnUrl, navigate: true);
    }
};
?>

    <div>  
     <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['name' => 'Listado Cronistas', 'route' => route('admin.cronistas.index')],
            ['name' => 'Editar Cronista'],
        ]" />
    </x-slot>
  
    @include('livewire.pages.admin.cronistas.form.form-cronista', ['showForm' => true,'editForm' => true, 'mode' => 'edit']) 
 
    </div>
