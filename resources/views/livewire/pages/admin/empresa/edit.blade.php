<?php

use Livewire\Volt\Component;
use App\Models\Empresa;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public $mode = 'edit';
    public $empresa;

    public $rif_letter, $rif_number, $rif;
    public $name, $razon_social, $direccion_fiscal, $latitud, $longitud, $oficina_principal, $horario_atencion;
    public $telefono_principal, $telefono_secundario;
    public $email_principal, $email_secundario;
    public $domain, $actividad, $description, $mision, $vision;
    public $organigrama, $organigrama_ruta;

    public function mount(Empresa $empresa)
    {
        $this->empresa = $empresa;
        $this->mode = 'edit';

        $this->name = $empresa->name;
        $this->razon_social = $empresa->razon_social;
        $this->direccion_fiscal = $empresa->direccion_fiscal;
        $this->latitud = $empresa->latitud;
        $this->longitud = $empresa->longitud;
        $this->oficina_principal = $empresa->oficina_principal;
        $this->horario_atencion = $empresa->horario_atencion;
        $this->telefono_principal = $empresa->telefono_principal;
        $this->telefono_secundario = $empresa->telefono_secundario;
        $this->email_principal = $empresa->email_principal;
        $this->email_secundario = $empresa->email_secundario;
        $this->domain = $empresa->domain;
        $this->actividad = $empresa->actividad;
        $this->description = $empresa->description;
        $this->mision = $empresa->mision;
        $this->vision = $empresa->vision;
        $this->organigrama_ruta = $empresa->organigrama_ruta;

        if ($empresa->rif) {
            $this->rif_letter = substr($empresa->rif, 0, 1);
            $this->rif_number = substr($empresa->rif, 1);
            $this->rif = $empresa->rif;
        }
    }

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'razon_social' => ['required', 'string', 'max:255'],
            'rif_letter' => ['required', 'in:J,G,C,V,E'],
            'rif_number' => ['required', 'regex:/^[0-9]{9}$/', 'string', 'digits:9'],
            'rif' => ['required', 'regex:/^[JGCVE][0-9]{9}$/', 'unique:empresa,rif,' . $this->empresa->id],
            'direccion_fiscal' => ['required', 'string'],
            'latitud' => ['required', 'numeric', 'between:-90,90'],
            'longitud' => ['required', 'numeric', 'between:-180,180'],
            'oficina_principal' => ['required', 'string'],
            'horario_atencion' => ['required', 'string'],          
            'telefono_principal' => ['required', 'regex:/^(\+?58)?-?\s*0?(412|414|416|424|426|29)\s*-?\d{7}$/' ],
            'telefono_secundario' => [ 'nullable', 'regex:/^(\+?58)?-?\s*0?(412|414|416|424|426|29)\s*-?\d{7}$/'],
            'email_principal' => ['required', 'email', 'max:255', 'unique:empresa,email_principal,' . $this->empresa->id],
            'email_secundario' => ['required', 'email', 'max:255', 'unique:empresa,email_secundario,' . $this->empresa->id],
            'domain' => ['required', 'string', 'max:255'],
            'actividad' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'mision' => ['required', 'string'],
            'vision' => ['required', 'string'],
            'organigrama' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
        ];
    }

    protected $messages = [
        'rif_letter.required' => 'Debes seleccionar la letra inicial del RIF.',
        'rif_letter.in' => 'La letra del RIF debe ser J, G, C, V o E.',
        'rif_number.required' => 'Debes ingresar los 9 números del RIF.',
        'rif_number.regex' => 'El RIF debe tener exactamente 9 dígitos numéricos.',
        'rif.required' => 'El RIF es obligatorio.',
        'rif.regex' => 'El RIF debe tener el formato correcto: una letra (J,G,C,V,E) seguida de 9 dígitos.',
        'rif.unique' => 'Ya existe una empresa registrada con este RIF.',

        'name.required' => 'El nombre comercial es obligatorio.',
        'razon_social.required' => 'La razón social es obligatoria.',
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
        'telefono_principal.regex' => 'El teléfono debe ser válido. Ejemplos: 04121234567, 4241234567, 0426-1234567, +584141234567',
        'telefono_secundario.regex' => 'El teléfono debe ser válido. Ejemplos: 04121234567, 4241234567, 0426-1234567, +584141234567',
        'email_principal.required' => 'El correo principal es obligatorio.',
        'email_principal.unique' => 'Ya existe una empresa registrada con este correo principal.',
        'email_secundario.required' => 'El correo secundario es obligatorio.',
        'email_secundario.unique' => 'Ya existe una empresa registrada con este correo secundario.',
        'organigrama.mimes' => 'El organigrama debe estar en formato PDF.',
        'organigrama.max' => 'El archivo PDF no puede exceder los 5 MB.',
    ];

    public function updateRifField()
    {
        if ($this->rif_letter && $this->rif_number) {
            $this->rif = strtoupper($this->rif_letter) . $this->rif_number;
        }
    }

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
                'rif_letter' => 'Letra del RIF',
                'rif_number' => 'Número del RIF',
                'rif' => 'RIF',
                'name' => 'Nombre Comercial',
                'razon_social' => 'Razón Social',
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

    private function formatPhone($phone)
    {
        if (!$phone) return null;    
        $phone = preg_replace('/\D+/', '', $phone);
        if (substr($phone, 0, 2) === '58') {
            $phone = '+' . $phone;
        }    
        elseif (substr($phone, 0, 1) === '0') {
            $phone = '+58' . substr($phone, 1);      
        }  
        elseif (strlen($phone) === 10 && substr($phone, 0, 1) === '4') {
            $phone = '+58' . $phone;
        }      
        elseif (strlen($phone) === 12 && substr($phone, 0, 2) === '58') {
            $phone = '+' . $phone;
        }    
        elseif (strlen($phone) === 10) {
            $phone = '+58' . $phone;        
        }     
        else {
            $phone = '+' . $phone;
        }
        return $phone;
    }

    public function save()
    {
        if (!$this->validateWithAlert()) return;

        try {
            $this->empresa->update([
                'name' => $this->name,
                'razon_social' => $this->razon_social,
                'rif' => $this->rif,
                'direccion_fiscal' => $this->direccion_fiscal,
                'latitud' => $this->latitud,
                'longitud' => $this->longitud,
                'oficina_principal' => $this->oficina_principal,
                'horario_atencion' => $this->horario_atencion,
                'telefono_principal' => $this->formatPhone($this->telefono_principal),
                'telefono_secundario' => $this->formatPhone($this->telefono_secundario),
                'email_principal' => $this->email_principal,
                'email_secundario' => $this->email_secundario,
                'domain' => $this->domain,
                'actividad' => $this->actividad,
                'description' => $this->description,
                'mision' => $this->mision,
                'vision' => $this->vision,
            ]);

            if ($this->organigrama) {
                $originalName = $this->organigrama->getClientOriginalName();
                $fileName = pathinfo($originalName, PATHINFO_FILENAME);
                $extension = $this->organigrama->getClientOriginalExtension();

                $baseName = $fileName;
                $counter = 1;

                while (\Storage::disk('public')->exists("organigramas/{$fileName}.{$extension}")) {
                    $fileName = $baseName . '_' . $counter;
                    $counter++;
                }

                $finalName = "{$fileName}.{$extension}";
                $filePath = $this->organigrama->storeAs('organigramas', $finalName, 'public');

                $this->empresa->update(['organigrama_ruta' => $filePath]);
                $this->organigrama_ruta = $filePath;
            }

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => '¡Datos actualizados!',
                'text' => 'La información de la empresa se guardó correctamente.',
                'timer' => 4000,
                'timerProgressBar' => true,
            ]);

            return $this->redirect(route('admin.empresa.index'), navigate: true);

        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error al actualizar',
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