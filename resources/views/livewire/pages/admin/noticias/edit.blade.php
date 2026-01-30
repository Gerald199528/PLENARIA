<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Models\Noticia;
use App\Models\Cronista;
use App\Models\Cronica;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithFileUploads;

    public $mode = 'edit';
    public $noticia_id;
    public $titulo, $contenido, $imagen, $imagen_actual;
    public $archivo_pdf, $archivo_pdf_actual;
    public $video_url, $video_archivo, $video_archivo_actual;
    public $tipo = '';
    public $tipo_video;
    public $destacada = false;
    public $fecha_publicacion;
    public $cronista_id, $cronica_id;
    public $prompt = '';
    public $contenidoGenerado = false;
    public $contenidoGeneradoTemporal = '';
    public $cronistas = [];
    public $cronicas = [];

    public function mount($noticia)
    {
        $noticia = Noticia::findOrFail($noticia);
        $this->noticia_id = $noticia->id;
        $this->titulo = $noticia->titulo;
        $this->contenido = $noticia->contenido;
        $this->imagen_actual = $noticia->imagen;
        $this->tipo = $noticia->tipo;
        $this->destacada = $noticia->destacada;
        $this->fecha_publicacion = $noticia->fecha_publicacion?->format('Y-m-d');
        $this->cronista_id = $noticia->cronista_id;
        $this->cronica_id = $noticia->cronica_id;
        $this->archivo_pdf_actual = $noticia->archivo_pdf;
        $this->video_url = $noticia->video_url;
        $this->video_archivo_actual = $noticia->video_archivo;
        $this->tipo_video = $noticia->tipo_video;
        
        $this->cronistas = Cronista::all();
        $this->cronicas = Cronica::all();
    }



    //------------------------------ AI GROQ GENERAR---------------------------------------------
    public function generarContenido()
    {
        // Validar que hay prompt
        if (empty($this->prompt)) {
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Campo requerido',
                'text' => 'Para generar contenido con IA, debes proporcionar una descripción detallada de lo que deseas crear. Por favor completa el campo de descripción.',
                'confirmButtonText' => 'Aceptar',
            ]);
            return;
        }

        try {
            $apiKey = config('services.groq.api_key');
            
            if (!$apiKey) {
                throw new \Exception('GROQ_API_KEY no configurada');
            }

            $userMessage = $this->prompt;

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
            ])->post(config('services.groq.api_url') . '/chat/completions', [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Eres un asistente de redacción profesional. Responde siempre en español. Genera contenido de alta calidad, bien estructurado y profesional.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $userMessage
                    ]
                ],
                'max_tokens' => 2048,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $this->contenidoGeneradoTemporal = $response->json()['choices'][0]['message']['content'];
                $this->contenidoGenerado = true;
                
                $this->dispatch('swal', [
                    'icon' => 'success',
                    'title' => '✅ Contenido generado',
                    'text' => 'Tu contenido ha sido generado exitosamente. Revisa y edita si es necesario.',
                    'confirmButtonText' => 'OK',
                ]);
            } else {
                throw new \Exception('Error en la API');
            }
        } catch (\Exception $e) {
            \Log::error('Groq Error: ' . $e->getMessage());
            $this->contenidoGenerado = false;
            
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => '❌ Error',
                'text' => 'Ocurrió un error al generar el contenido. Por favor intenta de nuevo.',
                'confirmButtonText' => 'Reintentar',
            ]);
        }
    }

    public function confirmarContenido()
    {
        $this->contenido = $this->contenidoGeneradoTemporal;
        $this->dispatch('closeModal', 'persistentModal');
        $this->reset(['prompt', 'contenidoGenerado', 'contenidoGeneradoTemporal']);
        
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '✅ Contenido confirmado',
            'text' => 'El contenido ha sido agregado al formulario correctamente.',
            'confirmButtonText' => 'OK',
        ]);
    }

    public function reiniciarModal()
    {
        $this->reset(['prompt', 'contenidoGeneradoTemporal', 'contenidoGenerado', 'imagen']);
    }



    protected function rules()
    {
        $rules = [
            'titulo' => 'required|string|min:5|max:255|regex:/^[a-zA-ZÀ-ÿ0-9\s\.\,\;\:\!\?\(\)\-]+$/',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tipo' => 'required|string|in:cronica,noticia,video,flyer',   
            'fecha_publicacion' => 'required|date',     
            'destacada' => 'boolean',
            'contenido' => 'required|string|min:20|max:5000',    
        ];

        if ($this->tipo === 'cronica') {
            $rules['cronista_id'] = 'required|exists:cronistas,id';
            $rules['cronica_id'] = 'required|exists:cronicas,id';
        }

        if ($this->tipo === 'noticia') {
            $rules['archivo_pdf'] = 'nullable|file|mimes:pdf|max:5120';
        }

        if ($this->tipo === 'video' || $this->tipo === 'cronica') {
            $rules['video_archivo'] = 'nullable|file|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime|max:20480';
            $rules['video_url'] = 'nullable|url|max:500';
        }

        return $rules;
    }

    protected $messages = [
        'titulo.required' => 'El título es obligatorio.',
        'titulo.min' => 'El título debe tener al menos 5 caracteres.',
        'titulo.max' => 'El título no puede exceder 255 caracteres.',
        'titulo.regex' => 'El título solo permite letras, números y signos básicos.',
        'contenido.required' => 'El contenido es obligatorio.',
        'contenido.min' => 'El contenido debe tener al menos 20 caracteres.',
        'contenido.max' => 'El contenido no puede exceder los 5000 caracteres.',
        'imagen.image' => 'Debe subir una imagen válida.',
        'imagen.mimes' => 'Solo se permiten imágenes JPEG, PNG, JPG o GIF.',
        'imagen.max' => 'La imagen no puede superar los 2MB.',
        'archivo_pdf.mimes' => 'El archivo debe ser un PDF válido.',
        'archivo_pdf.max' => 'El PDF no puede superar los 5MB.',
        'video_archivo.mimetypes' => 'El archivo de video debe ser MP4, AVI, MPEG o MOV.',
        'video_archivo.max' => 'El video no puede superar los 20MB.',
        'video_url.url' => 'Debe ser una URL válida para el video.',
        'video_url.max' => 'La URL no puede superar los 500 caracteres.',
        'tipo.required' => 'Debe seleccionar el tipo de publicación.',
        'tipo.in' => 'El tipo debe ser crónica, noticia, video o flyer.',
        'fecha_publicacion.required' => 'Debe indicar una fecha de publicación.',
        'fecha_publicacion.date' => 'Debe ingresar una fecha válida.',
        'destacada.boolean' => 'El valor de destacada debe ser verdadero o falso.',
        'cronista_id.required' => 'Debe seleccionar un cronista.',
        'cronista_id.exists' => 'El cronista seleccionado no existe.',
        'cronica_id.required' => 'Debe seleccionar una crónica.',
        'cronica_id.exists' => 'La crónica seleccionada no existe.',
    ];
    public function validateWithAlert()
    {
        try {
            // Solo validar video si es una nueva noticia o si se está cambiando el tipo a video
            if ($this->tipo === 'video' && !$this->video_archivo && !$this->video_url && !$this->video_archivo_actual) {
                $this->dispatch('showAlert', [
                    'icon' => 'error',
                    'title' => 'Error en Video',
                    'text' => 'Debes proporcionar un archivo de video o una URL para publicaciones de tipo video.',
                    'timer' => 8000,
                    'timerProgressBar' => true,
                ]);
                return false;
            }

            $this->validate();
            return true;
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors();
            $firstField = array_keys($errors->toArray())[0];
            $firstError = $errors->first($firstField);

            $fieldNames = [
                'titulo' => 'Título',
                'contenido' => 'Contenido',
                'imagen' => 'Imagen',
                'archivo_pdf' => 'Archivo PDF',
                'video_archivo' => 'Archivo de Video',
                'video_url' => 'URL del Video',
                'tipo' => 'Tipo de Publicación',
                'fecha_publicacion' => 'Fecha de Publicación',
                'cronista_id' => 'Cronista',
                'cronica_id' => 'Crónica',
                'destacada' => 'Destacada',
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
        if (!$this->validateWithAlert()) return;

        try {
            $noticia = Noticia::findOrFail($this->noticia_id);

            $imagenPath = $this->imagen_actual;
            if ($this->imagen) {
                if ($this->imagen_actual) {
                    Storage::disk('public')->delete($this->imagen_actual);
                }
                $originalName = $this->imagen->getClientOriginalName();
                $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                $finalName = pathinfo($originalName, PATHINFO_FILENAME) . '_' . time() . '.' . $extension;
                $imagenPath = $this->imagen->storeAs('noticias/imagenes', $finalName, 'public');
            }

            $pdfPath = $this->archivo_pdf_actual;
            if ($this->tipo === 'noticia' && $this->archivo_pdf) {
                if ($this->archivo_pdf_actual) {
                    Storage::disk('public')->delete($this->archivo_pdf_actual);
                }
                $originalName = $this->archivo_pdf->getClientOriginalName();
                $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                $finalName = pathinfo($originalName, PATHINFO_FILENAME) . '_' . time() . '.' . $extension;
                $pdfPath = $this->archivo_pdf->storeAs('noticias/pdf', $finalName, 'public');
            }

            $videoPath = $this->video_archivo_actual;
            $videoUrl = $this->video_url;
            $tipo_video = $this->tipo_video;

            if ($this->tipo === 'video' || $this->tipo === 'cronica') {
                if ($this->video_archivo && is_object($this->video_archivo)) {
                    if ($this->video_archivo_actual) {
                        Storage::disk('public')->delete($this->video_archivo_actual);
                    }
                    $originalName = $this->video_archivo->getClientOriginalName();
                    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                    $finalName = pathinfo($originalName, PATHINFO_FILENAME) . '_' . time() . '.' . $extension;
                    $videoPath = $this->video_archivo->storeAs('noticias/videos', $finalName, 'public');
                    $tipo_video = 'archivo';
                    $videoUrl = null;
                } elseif ($this->video_url && $this->tipo === 'video') {
                    $videoUrl = $this->video_url;
                    $videoPath = null;
                    $tipo_video = 'url';
                }
            }

            $noticiaData = [
                'titulo' => $this->titulo,
                'contenido' => $this->contenido,
                'imagen' => $imagenPath,
                'archivo_pdf' => $pdfPath,
                'video_url' => $videoUrl,
                'video_archivo' => $videoPath,
                'tipo_video' => $tipo_video,
                'tipo' => $this->tipo,
                'destacada' => $this->destacada,
                'fecha_publicacion' => $this->fecha_publicacion,
                'cronista_id' => $this->tipo === 'cronica' ? $this->cronista_id : null,
                'cronica_id' => $this->tipo === 'cronica' ? $this->cronica_id : null,
            ];

            $noticia->update($noticiaData);

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Éxito',
                'text' => 'Noticia actualizada con éxito.',
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
};

?>

<div>
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['name' => 'Editar Noticia'],
        ]" />
    </x-slot>

    @include('livewire.pages.admin.noticias.form.form', ['mode' => $mode])
</div>