<?php

use Livewire\Volt\Component;
use App\Models\Empresa;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public $mode = 'create';
    public $empresa;
    public $rif_letter = 'J';
    public $rif_number;
    public $rif; 

    public $name;
    public $latitud;
    public $longitud;
    public $razon_social;
    public $direccion_fiscal;
    public $oficina_principal;
    public $horario_atencion;
    public $telefono_principal;
    public $telefono_secundario;
    public $email_principal;
    public $email_secundario;
    public $domain;
    public $actividad;
    public $description = '';
    public $organigrama;
    public $organigrama_ruta;
    public $mision = '';
    public $vision = '';

    public function updated_rif_letter()
    {
        $this->updateRifField();
    }

    public function updated_rif_number()
    {
        $this->updateRifField();
    }

    public function updateRifField()
    {
        if ($this->rif_letter && $this->rif_number) {
            $this->rif = strtoupper($this->rif_letter . $this->rif_number);
        }
    }
    
    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'razon_social' => ['required', 'string', 'max:255'],        
            'rif_letter' => ['required', 'in:J,G,C,V,E'],
            'rif_number' => ['required', 'regex:/^[0-9]{8}[0-9]$/', 'string', 'digits:9'],
            'rif' => ['required', 'regex:/^[JGCVE][0-9]{9}$/', 'unique:empresa,rif'],
            'direccion_fiscal' => ['required', 'string'],
            'latitud' => ['required', 'numeric', 'between:-90,90'],
            'longitud' => ['required', 'numeric', 'between:-180,180'],
            'oficina_principal' => ['required', 'string'],
            'horario_atencion' => ['required', 'string'],
            'telefono_principal' => ['required', 'digits_between:10,11', 'regex:/^(0?4(1[0-6]|2[0-6]|9[0-9])\d{7})$/'],
            'telefono_secundario' => ['required', 'digits_between:10,11', 'regex:/^(0?4(1[0-6]|2[0-6]|9[0-9])\d{7})$/'],
            'email_principal' => ['required', 'email', 'max:255', 'unique:empresa,email_principal,' . ($this->empresa->id ?? '')],
            'email_secundario' => ['required', 'email', 'max:255', 'unique:empresa,email_secundario,' . ($this->empresa->id ?? '')],
            'domain' => ['required', 'string', 'max:255'],
            'actividad' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'mision' => ['required', 'string'],
            'vision' => ['required', 'string'],
            'organigrama' => ['required', 'file', 'mimes:pdf', 'max:5120'],
        ];
    }

    protected $messages = [
        'name.required' => 'El nombre comercial es obligatorio.',
        'razon_social.required' => 'La razón social es obligatoria.',
        'rif_letter.required' => 'Debes seleccionar la letra inicial del RIF.',
        'rif_letter.in' => 'La letra del RIF debe ser J, G, C, V o E.',
        'rif_number.required' => 'Debes ingresar los 9 números del RIF.',
        'rif_number.regex' => 'El RIF debe tener exactamente 9 dígitos numéricos.',
        'rif_number.digits' => 'El RIF debe tener exactamente 9 dígitos.',
        'rif.required' => 'El RIF es obligatorio.',
        'rif.regex' => 'El RIF debe tener el formato correcto: una letra (J,G,C,V,E) seguida de 9 dígitos. Ejemplo: J123456789',
        'rif.unique' => 'Ya existe una empresa registrada con este RIF.',

        'direccion_fiscal.required' => 'Debe indicar la dirección fiscal.',
        'latitud.required' => 'La latitud es obligatoria.',
        'latitud.numeric' => 'La latitud debe ser un número válido.',
        'latitud.between' => 'La latitud debe estar entre -90 y 90.',
        'longitud.required' => 'La longitud es obligatoria.',
        'longitud.numeric' => 'La longitud debe ser un número válido.',
        'longitud.between' => 'La longitud debe estar entre -180 y 180.',
        'oficina_principal.required' => 'Debe especificar la oficina principal.',
        'horario_atencion.required' => 'Debe indicar el horario de atención.',

        'telefono_principal.required' => 'Debe ingresar un número de teléfono principal.',
        'telefono_principal.digits_between' => 'El teléfono principal debe tener entre 10 y 11 dígitos.',
        'telefono_principal.regex' => 'El teléfono principal debe ser un operador móvil válido Movistar (0414/0424), Movilnet (0416/0426), Digitel (0412).',
        'telefono_secundario.required' => 'Debe ingresar un número de teléfono secundario.',
        'telefono_secundario.digits_between' => 'El teléfono secundario debe tener entre 10 y 11 dígitos.',
        'telefono_secundario.regex' => 'El teléfono secundario debe ser un operador móvil válido Movistar (0414/0424), Movilnet (0416/0426), Digitel (0412).',

        'email_principal.required' => 'El correo principal es obligatorio.',
        'email_principal.email' => 'Debe ingresar un correo principal válido.',
        'email_principal.unique' => 'Ya existe una empresa registrada con este correo principal.',
        'email_secundario.required' => 'El correo secundario es obligatorio.',
        'email_secundario.email' => 'Debe ingresar un correo secundario válido.',
        'email_secundario.unique' => 'Ya existe una empresa registrada con este correo secundario.',

        'domain.required' => 'El dominio es obligatorio.',
        'actividad.required' => 'Debe especificar la actividad económica.',
        'description.required' => 'Debe ingresar la descripción de la empresa.',
        'mision.required' => 'Debe ingresar la misión de la empresa.',
        'vision.required' => 'Debe ingresar la visión de la empresa.',

        'organigrama.required' => 'Debe subir el archivo del organigrama en formato PDF.',
        'organigrama.mimes' => 'El organigrama debe estar en formato PDF.',
        'organigrama.max' => 'El archivo PDF no puede exceder los 5 MB.',
    ];

    public function validateWithAlert()
    {
        try {         
            $this->updateRifField();
            $this->validate($this->rules(), $this->messages);
            return true;
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors();
            $firstField = array_keys($errors->toArray())[0];
            $firstError = $errors->first($firstField);

            $fieldNames = [
                'name' => 'Nombre Comercial',
                'razon_social' => 'Razón Social',
                'rif_letter' => 'Letra del RIF',
                'rif_number' => 'Números del RIF',
                'rif' => 'RIF',
                'direccion_fiscal' => 'Dirección Fiscal',
                'latitud' => 'Latitud',
                'longitud' => 'Longitud',
                'oficina_principal' => 'Oficina Principal',
                'horario_atencion' => 'Horario de Atención',
                'telefono_principal' => 'Teléfono Principal',
                'telefono_secundario' => 'Teléfono Secundario',
                'email_principal' => 'Correo Principal',
                'email_secundario' => 'Correo Secundario',
                'domain' => 'Dominio',
                'actividad' => 'Actividad',
                'description' => 'Descripción',
                'mision' => 'Misión',
                'vision' => 'Visión',
                'organigrama' => 'Organigrama (PDF)',
            ];

            $fieldTitle = $fieldNames[$firstField] ?? ucfirst(str_replace('_', ' ', $firstField));

            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error en ' . $fieldTitle,
                'text' => $firstError,
                'timer' => 8000,
                'timerProgressBar' => true,
            ]);

            return false;
        }
    }

    public function save()
    {
        $this->updateRifField();
        if(Empresa::count() > 0) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Empresa ya registrada',
                'text' => 'Solo se puede registrar una empresa en el sistema.',
                'timer' => 8000,
                'timerProgressBar' => true,
            ]);
            return; 
        }

        if (!$this->validateWithAlert()) return;

        try {    
            $this->rif = strtoupper($this->rif_letter . $this->rif_number);
            $filePath = $this->organigrama_ruta;
            if ($this->organigrama) {
                $originalFileName = $this->organigrama->getClientOriginalName();
                $sanitizedFileName = pathinfo($originalFileName, PATHINFO_FILENAME);
                $extension = $this->organigrama->getClientOriginalExtension();
                $fileNameToStore = $sanitizedFileName . '_' . time() . '.' . $extension;
                $filePath = $this->organigrama->storeAs('organigramas', $fileNameToStore, 'public');
            }   
            $data = [
                'name' => $this->name,
                'razon_social' => $this->razon_social,
                'rif' => $this->rif,
                'direccion_fiscal' => $this->direccion_fiscal,
                'latitud' => $this->latitud,
                'longitud' => $this->longitud,
                'oficina_principal' => $this->oficina_principal,
                'horario_atencion' => $this->horario_atencion,
                'telefono_principal' => $this->telefono_principal ? (preg_match('/^(0?4(1[0-6]|2[0-6]|9[0-9])\d{7})$/', $this->telefono_principal) ? '+58' . ltrim($this->telefono_principal, '0') : null) : null,
                'telefono_secundario' => $this->telefono_secundario ? (preg_match('/^(0?4(1[0-6]|2[0-6]|9[0-9])\d{7})$/', $this->telefono_secundario) ? '+58' . ltrim($this->telefono_secundario, '0') : null) : null,
                'email_principal' => $this->email_principal,
                'email_secundario' => $this->email_secundario,
                'domain' => $this->domain,
                'actividad' => $this->actividad,
                'description' => $this->description,
                'organigrama_ruta' => $filePath,
                'mision' => $this->mision,
                'vision' => $this->vision,
            ];    
            \App\Models\Empresa::create($data);

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => '¡Empresa registrada!',
                'text' => 'La empresa se ha guardado correctamente.',
                'timer' => 4000,
                'timerProgressBar' => true,
            ]);

            return $this->redirect(route('admin.empresa.index'), navigate: true);

        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error al guardar',
                'text' => 'Ocurrió un problema: ' . $e->getMessage(),
                'timer' => 8000,
                'timerProgressBar' => true,
            ]);
        }
    }

    public function cancel()
    {
        return $this->redirect(route('admin.empresa.index'), navigate: true);
    }

    public function limpiar()
    {
        $this->reset([
            'rif_letter', 'rif_number', 'rif',
            'name', 'razon_social', 'direccion_fiscal', 'latitud', 'longitud', 'oficina_principal', 'horario_atencion',
            'telefono_principal', 'telefono_secundario',
            'email_principal', 'email_secundario',
            'domain', 'actividad', 'description', 'mision', 'vision',
            'organigrama', 'organigrama_ruta'
        ]);

        $this->dispatch('showAlert', [
            'icon' => 'info',
            'title' => 'Formulario limpio',
            'text' => 'Se han borrado todos los campos del formulario.',
            'timer' => 2000,
            'timerProgressBar' => true,
        ]);
    }

};
?>
<div>
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['name' => 'Datos de la empresa'],
        ]" />
    </x-slot>
    @include('livewire.pages.admin.empresa.form.form', ['mode' => $mode])
</div>