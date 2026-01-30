<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Models\Cronica;
use App\Models\Cronista;
use App\Models\CategoriaCronica;

new class extends Component {
    use WithFileUploads;

    public Cronica $cronica;

    public $cronistas;
    public $categorias;

    public $titulo;
    public $contenido;
    public $archivo_pdf;
    public $cronista_id;
    public $categoria_id;
    public $fecha_publicacion;
    public $cronista_nombre;

    public $mode = 'edit';

    public function mount(Cronica $cronica)
    {
        $this->cronica = $cronica;

        $this->cronistas = Cronista::all();
        $this->categorias = CategoriaCronica::all();

        // Inicializar campos con valores existentes
        $this->titulo = $cronica->titulo;
        $this->contenido = $cronica->contenido;
        $this->categoria_id = $cronica->categoria_id;
        $this->fecha_publicacion = $cronica->fecha_publicacion;
        $this->cronista_id = $cronica->cronista_id;
        $this->cronista_nombre = $cronica->cronista 
            ? $cronica->cronista->nombre_completo . ' ' . $cronica->cronista->apellido_completo
            : '';
    }

    protected function rules()
    {
        return [
            'titulo' => 'required|string|min:5|max:255|unique:cronicas,titulo,' . $this->cronica->id,
            'contenido' => 'required|string|min:20|max:5000',
            'categoria_id' => 'required|exists:categoria_cronicas,id',
            'fecha_publicacion' => 'required|date',
            'archivo_pdf' => 'nullable|file|mimes:pdf|max:5120|unique:cronicas,archivo_pdf,' . $this->cronica->id,
        ];
    }

    protected $messages = [
        'titulo.required' => 'El título es obligatorio.',
        'contenido.required' => 'El contenido es obligatorio.',
        'contenido.min' => 'El contenido debe tener al menos 20 caracteres.',
        'contenido.max' => 'El contenido no puede superar 5000 caracteres.',
        'archivo_pdf.mimes' => 'El archivo debe ser un PDF válido.',
        'archivo_pdf.max' => 'El archivo no puede exceder 5MB.',
        'categoria_id.required' => 'Debe seleccionar una categoría.',
        'fecha_publicacion.required' => 'Debe seleccionar la fecha de publicación.',
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
                'titulo' => 'Título',
                'contenido' => 'Contenido',
                'archivo_pdf' => 'Archivo PDF',
                'cronista_id' => 'Cronista',
                'categoria_id' => 'Categoría',
                'fecha_publicacion' => 'Fecha de Publicación',
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
    // Validación con alertas
    if (!$this->validateWithAlert()) return;

    try {
        // Manejo de archivo PDF si se sube uno nuevo
        $filePath = $this->cronica->archivo_pdf;
        if ($this->archivo_pdf) {
            if ($filePath && \Storage::disk('public')->exists($filePath)) {
                \Storage::disk('public')->delete($filePath);
            }
            $originalName = $this->archivo_pdf->getClientOriginalName();
            $filePath = $this->archivo_pdf->storeAs(
                'cronicas', 
                pathinfo($originalName, PATHINFO_FILENAME) . '_' . time() . '.' . $this->archivo_pdf->getClientOriginalExtension(), 
                'public'
            );
        }

        // Actualizar registro
        $this->cronica->update([
            'titulo' => $this->titulo,
            'contenido' => $this->contenido,
            'categoria_id' => $this->categoria_id,
            'fecha_publicacion' => $this->fecha_publicacion,
            'archivo_pdf' => $filePath,
        ]);

        // Alerta de éxito
        $this->dispatch('showAlert', [
            'icon' => 'success',
            'title' => 'Crónica actualizada',
            'text' => 'Los cambios se guardaron correctamente.',
            'timer' => 3000,
            'timerProgressBar' => true,
        ]);

        return $this->redirect(route('admin.cronicas.index'), navigate: true);

    } catch (\Exception $e) {
        $this->dispatch('showAlert', [
            'icon' => 'error',
            'title' => 'Error',
            'text' => 'Ocurrió un problema: ' . $e->getMessage(),
            'timer' => 8000,
            'timerProgressBar' => true,
        ]);
    }
}

public function cancel()
{
    return $this->redirect(route('admin.cronicas.index'), navigate: true);
}

};
?>

<div>
      <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['name' => 'Listado Cronicas', 'route' => route('admin.categoria_cronicas.index')],
            ['name' => 'Editar Cronica'],
        ]" />
    </x-slot>

    @include('livewire.pages.admin.cronicas.form.form', [
        'mode' => 'edit'
    ])
</div>

