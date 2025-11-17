<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Models\Noticia;
use App\Models\Cronista;
use App\Models\Cronica;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithFileUploads;

    public $mode = 'create';
    public $noticia_id;
    public $titulo, $contenido, $imagen, $imagen_actual;
    public $archivo_pdf, $archivo_pdf_actual;
    public $video_url, $video_archivo, $video_archivo_actual;
    public $tipo = '';
    public $tipo_video;
    public $destacada;
    public $fecha_publicacion;
    public $cronista_id, $cronica_id;
    
    public $cronistas = [];
    public $cronicas = [];

    public function mount()
    {
        $this->cronistas = Cronista::all();
        $this->cronicas = Cronica::all();
        $this->tipo = '';
        $this->destacada = null;
    }  
    protected function rules()
    {    
        $rules = [
            'titulo' => 'required|string|min:5|max:255|regex:/^[a-zA-ZÀ-ÿ0-9\s\.\,\;\:\!\?\(\)\-]+$/',
            'imagen' => ($this->mode === 'create' ? 'required' : 'nullable') . '|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tipo' => 'required|string|in:cronica,noticia,video,flyer',   
            'fecha_publicacion' => 'required|date',     
            'destacada' => 'required|boolean',
            'contenido' => 'required|string|min:20|max:5000',    
        ];

        // Si es CRÓNICA → cronista y cronica obligatorios
        if ($this->tipo === 'cronica') {
            $rules['cronista_id'] = 'required|exists:cronistas,id';
            $rules['cronica_id'] = 'required|exists:cronicas,id';
        }

        // Si es NOTICIA → PDF opcional
        if ($this->tipo === 'noticia') {
            $rules['archivo_pdf'] = 'nullable|file|mimes:pdf|max:5120';
        }

        // Reglas para video (tipo = 'video' O 'cronica')
        if ($this->tipo === 'video' || $this->tipo === 'cronica') {
            $rules['video_archivo'] = 'nullable|file|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime|max:20480';
            $rules['video_url'] = 'nullable|url|max:500';
        }

        // Si es FLYER → solo campos base
        if ($this->tipo === 'flyer') {
            // No se agregan más reglas
        }

        return $rules;
    }

    protected $messages = [
        // 🔹 Título
        'titulo.required' => 'El título es obligatorio.',
        'titulo.min' => 'El título debe tener al menos 5 caracteres.',
        'titulo.max' => 'El título no puede exceder 255 caracteres.',
        'titulo.regex' => 'El título solo permite letras, números y signos básicos.',

        // Contenido
        'contenido.required' => 'El contenido es obligatorio.',
        'contenido.min' => 'El contenido debe tener al menos 20 caracteres.',
        'contenido.max' => 'El contenido no puede exceder los 5000 caracteres.',

        // Imagen
        'imagen.required' => 'La imagen principal es obligatoria.',
        'imagen.image' => 'Debe subir una imagen válida.',
        'imagen.mimes' => 'Solo se permiten imágenes JPEG, PNG, JPG o GIF.',
        'imagen.max' => 'La imagen no puede superar los 2MB.',

        // Archivo PDF (para noticias)
        'archivo_pdf.mimes' => 'El archivo debe ser un PDF válido.',
        'archivo_pdf.max' => 'El PDF no puede superar los 5MB.',

        // Video (para tipo video)
        'video_archivo.mimetypes' => 'El archivo de video debe ser MP4, AVI, MPEG o MOV.',
        'video_archivo.max' => 'El video no puede superar los 20MB.',
        'video_url.url' => 'Debe ser una URL válida para el video.',
        'video_url.max' => 'La URL no puede superar los 500 caracteres.',
        'video_required_custom' => 'Debes subir un archivo de video o proporcionar una URL.',

        // Tipo de publicación
        'tipo.required' => 'Debe seleccionar el tipo de publicación.',
        'tipo.in' => 'El tipo debe ser crónica, noticia, video o flyer.',

        // Fecha de publicación
        'fecha_publicacion.required' => 'Debe indicar una fecha de publicación.',
        'fecha_publicacion.date' => 'Debe ingresar una fecha válida.',

        // Destacada - AHORA REQUIRED
        'destacada.required' => 'La noticia destacada es obligatoria.',
     
        // Cronista (solo crónica)
        'cronista_id.required' => 'Debe seleccionar un cronista.',
        'cronista_id.exists' => 'El cronista seleccionado no existe.',

        // Crónica (solo crónica)
        'cronica_id.required' => 'Debe seleccionar una crónica.',
        'cronica_id.exists' => 'La crónica seleccionada no existe.',
    ];

    public function validateWithAlert()
    {
        try {
            // Validación personalizada para video (obligatorio solo si tipo = 'video')
            if ($this->tipo === 'video' && !$this->video_archivo && !$this->video_url) {
                $this->dispatch('showAlert', [
                    'icon' => 'error',
                    'title' => 'Error en Archivo de Video',
                    'text' => 'Debes subir un archivo de video o proporcionar una URL.',
                    'timer' => 8000,
                    'timerProgressBar' => true,
                ]);
                return false;            
            }

            // Ejecuta la validación según las reglas dinámicas definidas
            $this->validate();
            return true;
        } catch (\Illuminate\Validation\ValidationException $e) {

            // Obtiene los errores y localiza el primero
            $errors = $e->validator->errors();
            $firstField = array_keys($errors->toArray())[0];
            $firstError = $errors->first($firstField);

            // Nombres amigables para los campos
            $fieldNames = [
                'titulo' => 'Título',
                'contenido' => 'Contenido',
                'imagen' => 'Imagen',
                'archivo_pdf' => 'Archivo PDF',
                'video_archivo' => 'Archivo de Video',
                'video_url' => 'URL del Video',
                'tipo' => 'Tipo de Publicación',
                'fecha_publicacion' => 'Fecha de Publicación',
                'destacada' => 'Destacada',
                'cronista_id' => 'Cronista',
                'cronica_id' => 'Crónica',
            ];

            // Título del campo que causó el error
            $fieldTitle = $fieldNames[$firstField] ?? 'Campo';

            // Envía alerta visual al usuario
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
        if (!$this->validateWithAlert()) return;

        try {
            //  Manejo de imagen principal - NOMBRE ORIGINAL
            $imagenPath = $this->imagen_actual;
            if ($this->imagen) {
                $originalName = $this->imagen->getClientOriginalName();
                $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                $finalName = pathinfo($originalName, PATHINFO_FILENAME) . '_' . time() . '.' . $extension;
                $imagenPath = $this->imagen->storeAs('noticias/imagenes', $finalName, 'public');
            }

            //  Manejo del archivo PDF - NOMBRE ORIGINAL
            $pdfPath = $this->archivo_pdf_actual;
            if ($this->tipo === 'noticia' && $this->archivo_pdf) {
                $originalName = $this->archivo_pdf->getClientOriginalName();
                $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                $finalName = pathinfo($originalName, PATHINFO_FILENAME) . '_' . time() . '.' . $extension;
                $pdfPath = $this->archivo_pdf->storeAs('noticias/pdf', $finalName, 'public');
            }

            //  Manejo del video - NOMBRE ORIGINAL
            $videoPath = $this->video_archivo_actual;
            $videoUrl = null;
            $this->tipo_video = null;

            if ($this->tipo === 'video' || $this->tipo === 'cronica') {
                if ($this->video_archivo && is_object($this->video_archivo)) {
                    try {
                        $originalName = $this->video_archivo->getClientOriginalName();
                        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                        $finalName = pathinfo($originalName, PATHINFO_FILENAME) . '_' . time() . '.' . $extension;
                        $videoPath = $this->video_archivo->storeAs('noticias/videos', $finalName, 'public');
                        $this->tipo_video = 'archivo';
                    } catch (\Exception $e) {
                        throw new \Exception("Error al guardar el archivo de video: " . $e->getMessage());
                    }
                } elseif ($this->video_url && $this->tipo === 'video') {
                    $videoUrl = $this->video_url;
                    $this->tipo_video = 'url';
                }
            }
        
            $noticiaData = [
                'titulo' => $this->titulo,
                'contenido' => $this->contenido,
                'imagen' => $imagenPath,
                'archivo_pdf' => $pdfPath,
                'video_url' => $videoUrl,
                'video_archivo' => $videoPath,
                'tipo_video' => $this->tipo_video,
                'tipo' => $this->tipo,
                'destacada' => $this->destacada,
                'fecha_publicacion' => $this->fecha_publicacion,
                'cronista_id' => $this->tipo === 'cronica' ? $this->cronista_id : null,
                'cronica_id' => $this->tipo === 'cronica' ? $this->cronica_id : null,
            ];        
            Noticia::create($noticiaData);

            // 🧹 Limpiar formulario después de guardar
            $this->limpiar();

        
            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Éxito',
                'text' => 'Noticia creada con éxito.',
                'timer' => 2000,
                'timerProgressBar' => true,
            ]);         
            return $this->redirect(route('admin.noticias.index'), navigate: true);

        } catch (\Exception $e) { 
            
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error: ' . $e->getMessage(),
                'timer' => 4000,
                'timerProgressBar' => true,
            ]);
        }
    }

    public function cancel()
    {
        return $this->redirect(route('admin.noticias.index'), navigate: true);
    }

    public function limpiar()
    {
        $this->reset([
            'titulo', 'contenido', 'imagen', 'archivo_pdf', 
            'video_url', 'video_archivo', 'tipo', 'destacada', 
            'fecha_publicacion', 'cronista_id', 'cronica_id'
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
            ['name' => 'Crear Noticia'],
        ]" />
    </x-slot>
    @include('livewire.pages.admin.noticias.form.form', ['mode' => $mode])
</div>