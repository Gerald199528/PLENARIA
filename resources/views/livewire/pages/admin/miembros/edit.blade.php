<?php

use Livewire\Volt\Component;
use App\Models\Miembro;
use App\Models\Concejal;
use App\Models\Comision;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;

new #[Title('Editar Miembro')] class extends Component
{
    public Miembro $miembro;
    // Campos del formulario
    public $concejal_id;
    public $comision_id;
    public $fecha_inicio;
    public $fecha_fin;
    public $estado;
    public $nueva_comision;
    public $descripcion_comision;
    // Listas
    public $concejales;
    public $comisiones;

    public function mount(Miembro $miembro)
    {
        $this->miembro = $miembro;
        $this->concejales = Concejal::all();
        $this->comisiones = Comision::all();
        $this->fecha_inicio = $miembro->fecha_inicio ?? null;
        $this->fecha_fin = $miembro->fecha_fin ?? null;
        $this->estado = $miembro->estado ?? 'Activo';
        // Obtener el miembro desde la tabla comision_concejal
        $miembro = DB::table('comision_concejal')
            ->where('miembro_id', $this->miembro->id)
            ->first();
        // Inicializar campos con datos del miembro
        $this->concejal_id = $miembro->concejal_id ?? '';
        $this->comision_id = $miembro->comision_id ?? '';            
        }


    protected function rules()
    {
        return [
            'concejal_id' => 'required|exists:concejal,id',
            'comision_id' => 'required|exists:comisions,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'estado' => 'required|in:Activo,Inactivo',
        ];
    }

    protected $messages = [
        'concejal_id.required' => 'Debe seleccionar un concejal.',
        'concejal_id.exists' => 'El concejal seleccionado no existe.',
        'comision_id.required' => 'Debe seleccionar una comisión.',
        'comision_id.exists' => 'La comisión seleccionada no existe.',
        'fecha_inicio.required' => 'Debe indicar la fecha de inicio.',
        'fecha_fin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
        'estado.in' => 'Estado inválido.',
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
                'concejal_id' => 'Concejal',
                'comision_id' => 'Comisión',                    
                'fecha_inicio' => 'Fecha de Inicio',
                'fecha_fin' => 'Fecha de Fin',
                'estado' => 'Estado',
            ];
            $fieldTitle = $fieldNames[$firstField] ?? 'Campo';
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
    // Validación con alerta personalizada
    if (!$this->validateWithAlert()) {
        return; 
    }

    try {
        // Actualizamos la tabla miembros
        $this->miembro->update([
            'concejal_id' => $this->concejal_id,
            'comision_id' => $this->comision_id,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'estado' => $this->estado,
        ]);
        // Actualizamos la tabla pivote comision_concejal
        DB::table('comision_concejal')->updateOrInsert(
            ['miembro_id' => $this->miembro->id],
            [
                'miembro_id'      => $this->miembro->id,
                'concejal_id'     => $this->concejal_id,
                'comision_id'     => $this->comision_id,
                'nombre_concejal' => DB::table('concejal')->where('id', $this->concejal_id)->value('nombre'),
                'cedula_concejal' => DB::table('concejal')->where('id', $this->concejal_id)->value('cedula'),
                'nombre_comision' => DB::table('comisions')->where('id', $this->comision_id)->value('nombre'),
                'updated_at'      => now(),
            ]
        );
        // Alerta de éxito
        $this->dispatch('showAlert', [
            'icon' => 'success',
            'title' => 'Éxito',
            'text' => 'Miembro y tabla pivote actualizados correctamente.',
            'timer' => 3000,
            'timerProgressBar' => true,
        ]);
        // Redirigir al índice
        return $this->redirect(route('admin.miembros.index'), navigate: true);

    } catch (\Exception $e) {
        // Captura cualquier error inesperado y muestra alerta
        $this->dispatch('showAlert', [
            'icon' => 'error',
            'title' => 'Error',
            'text' => 'Ocurrió un error al guardar: ' . $e->getMessage(),
            'timer' => 5000,
            'timerProgressBar' => true,
        ]);
    }
}

        public function toggleEstado()
    {
        $this->estado = $this->estado === 'Activo' ? 'Inactivo' : 'Activo';
    }


    public function cancel()
    {
        return $this->redirect(route('admin.miembros.index'), navigate: true);
    }
    };
    ?>
    <div>
           <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['name' => 'Miembros', 'route' => route('admin.miembros.index')],
            ['name' => 'Editar miembro'],
        ]" />
    </x-slot>
            @include('livewire.pages.admin.miembros.from.from', [ 'showForm' => true, 'editForm' => true, 'mode' => 'edit' ])
    </div>


