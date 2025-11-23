<?php

use Livewire\Volt\Component;
use App\Models\Miembro;
use App\Models\Concejal;
use App\Models\Comision;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public $mode = 'create';    
    // Campos del formulario
    public $concejal_id;
    public $comision_id;
    public $nueva_comision = '';
    public $descripcion_comision = '';
    public $cargo;
    public $fecha_inicio;
    public $fecha_fin;
    public $estado = 'Activo'; 

    //------- Listas para selects-------
    public $concejales = [];
    public $comisiones = [];   
    

 /**-------------------------------------------------------------

 * - Carga todos los concejales y comisiones disponibles.
 * - Si se pasa un miembro, configura el modo de edición:
 */
    public function mount($miembro = null)
    {
        $this->concejales = Concejal::all();
        $this->comisiones = Comision::all();

        if ($miembro) {
            $this->mode = 'edit';
            $this->miembro_id = $miembro->id;
            $this->fecha_inicio = $miembro->fecha_inicio;
            $this->fecha_fin = $miembro->fecha_fin;
            $this->estado = $miembro->estado ?? 'Activo';

            $registro = DB::table('comision_concejal')
                ->where('miembro_id', $miembro->id)
                ->first();

            if ($registro) {
                $this->concejal_id = $registro->concejal_id;
                $this->comision_id = $registro->comision_id;
                $this->cargo = DB::table('concejal')->where('id', $this->concejal_id)->value('cargo');
            }
        }
    }
 //-----------------------------------------------------------------------

/**-------------------------------------------------------------

 * Validacion de campos
 */

protected function rules()
{
    $concejalTable = (new Concejal())->getTable();
    $comisionTable = (new Comision())->getTable();
    
    return [
        'concejal_id' => ['required',Rule::unique('comision_concejal')->where(function ($query) {
        return $query->where('comision_id', $this->comision_id); }),],
        'comision_id' => "required_without:nueva_comision|exists:{$comisionTable},id",
        'nueva_comision' => 'required_without:comision_id|string|max:255',
        'descripcion_comision' => 'required_with:nueva_comision|string|max:500',
        'fecha_inicio' => 'required|date',
        'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        'estado' => 'required|in:Activo',
    ];
}
protected function messages()
{
    return [
        'concejal_id.required' => 'Debe seleccionar un concejal',
        'concejal_id.exists' => 'El concejal seleccionado no existe',
        'concejal_id.unique' => 'El concejal ya pertenece a esta comisión',
        'comision_id.required_without' => 'Debe seleccionar una comisión o crear una nueva',
        'comision_id.exists' => 'La comisión seleccionada no existe',
        'nueva_comision.required_without' => 'Debe ingresar el nombre de la nueva comisión o seleccionar una existente',
        'descripcion_comision.required_with' => 'Debe ingresar la descripción de la nueva comisión',
        'fecha_inicio.required' => 'La fecha de inicio es obligatoria',
        'fecha_inicio.date' => 'La fecha de inicio debe ser una fecha válida',
        'fecha_fin.date' => 'La fecha de fin debe ser una fecha válida',
        'fecha_fin.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio',
        'estado.required' => 'Debe seleccionar el estado del miembro',
        'estado.in' => 'El estado debe ser Activo ',      
    ];
}
 //-----------------------------------------------------------------------


    /**-------------------------------------------------------------

 * -validacion de campos con sweeralert
 */

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
                'nueva_comision' => 'Nueva Comisión',
                'descripcion_comision' => 'Descripción de la Comisión',
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
 //-----------------------------------------------------------------------

  /**-------------------------------------------------------------

 * Metodo guardar la inforamcion del formulario en general
 */
  
    public function save()
    {
        if (!$this->validateWithAlert()) return;
        try {
            // Manejar nueva comisión si aplica
            if($this->nueva_comision) {
                $comision = Comision::create([
                    'nombre' => $this->nueva_comision,
                    'descripcion' => $this->descripcion_comision ?: null,
                ]);
                $this->comision_id = $comision->id;
            }
            if ($this->mode === 'create') {
                $miembro = Miembro::create([
                    'fecha_inicio' => $this->fecha_inicio,
                    'fecha_fin' => $this->fecha_fin,
                    'estado' => $this->estado,
                ]);
                            
            /**-------------------------------------------------------------

            * MKetodo para gaurdar en mi tabla pivote comision_concejal 
            */
                    DB::table('comision_concejal')->insert([
                    'miembro_id'      => $miembro->id,
                    'concejal_id'     => $this->concejal_id,
                    'comision_id'     => $this->comision_id,
                    'nombre_concejal' => DB::table('concejal')->where('id', $this->concejal_id)->value('nombre'),
                    'cedula_concejal' => DB::table('concejal')->where('id', $this->concejal_id)->value('cedula'),
                    'nombre_comision' => DB::table('comisions')->where('id', $this->comision_id)->value('nombre'),
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);                                   
            //-------------------------------------------------------------
            }
                $this->dispatch('showAlert', [
                'icon'=>'success',
                'title'=>'Éxito',
                'text'=>'Miembro registrado con éxito.',
                'timer'=>2000,
                'timerProgressBar'=>true,
            ]);

            return $this->redirect(route('admin.miembros.index'), navigate: true);

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
 //-----------------------------------------------------------------------


 /**-------------------------------------------------------------
 *Estado  del cocnejal
 */
        public function toggleEstado()
    {
        $this->estado = $this->estado === 'Activo' ? 'Inactivo' : 'Activo';
    }
 //-----------------------------------------------------------------------

  /**-------------------------------------------------------------
 *Limpair formulario
 */

    public function limpiar($showAlert=true)
    {
        $this->reset([
            'concejal_id',
            'comision_id',
            'nueva_comision',
            'descripcion_comision',
            'cargo',
            'fecha_inicio',
            'fecha_fin'
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
    public function cancel()

    {     return $this->redirect(route('admin.miembros.index'), navigate: true);

    }
    }
    ;?>
    <div>
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            [
                'name' => 'Dashboard',
                'route' => route('admin.dashboard'),
            ],
            [
                'name' => ' Registrar Miembro',
            ],
        ]" />
    </x-slot>

        @include('livewire.pages.admin.miembros.from.from', ['mode' => $mode])
    </div>
