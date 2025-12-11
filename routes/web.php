<?php
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\InstrumentosLegalesController;
use App\Http\Controllers\NoticiaCompletaController;
use App\Http\Controllers\DerechodePalabraController;
use App\Http\Controllers\SesionMunicipalController;

Route::get('/', function () {
    $empresa = \App\Models\Empresa::first();
    $organigramaSize = null;
    
    if ($empresa && $empresa->organigrama_ruta) {
        $filePath = storage_path('app/public/' . $empresa->organigrama_ruta);
        if (file_exists($filePath)) {
            $organigramaSize = round(filesize($filePath) / 1048576, 2);
        }
    }
    
    // Obtener la noticia destacada principal
    $noticiaPrincipal = \App\Models\Noticia::with(['cronista', 'cronica'])
        ->where('destacada', true)
        ->latest('fecha_publicacion')
        ->first();
    
    // Obtener noticias secundarias destacadas
    $noticiasSecundarias = \App\Models\Noticia::with(['cronista', 'cronica'])
        ->where('destacada', true)
        ->latest('fecha_publicacion')
        ->skip(1)
        ->take(3)
        ->get();
    
    // Obtener todas las noticias destacadas para el carrusel
    $noticias = \App\Models\Noticia::with(['cronista', 'cronica'])
        ->where('destacada', true)
        ->latest('fecha_publicacion')
        ->get();

    // Obtener FLYERS (SIN FILTRO DE DESTACADA)
    $flyers = \App\Models\Noticia::with(['cronista', 'cronica'])
        ->where('tipo', 'flyer')
        ->latest('fecha_publicacion')
        ->get();

    // Obtener CRÓNICAS (SIN FILTRO DE DESTACADA)
    $cronicas = \App\Models\Noticia::with(['cronista', 'cronica.cronista'])
        ->whereNotNull('cronica_id')
        ->latest('fecha_publicacion')
        ->get()
        ->map(function ($noticia) {
            return (object)[
                'id' => $noticia->id,
                'titulo' => $noticia->cronica ? $noticia->cronica->titulo : $noticia->titulo,
                'contenido' => $noticia->cronica ? $noticia->cronica->contenido : $noticia->contenido,
                'imagen' => $noticia->imagen ?? ($noticia->cronica && $noticia->cronica->cronista ? $noticia->cronica->cronista->foto : null),
                'fecha_publicacion' => $noticia->fecha_publicacion,
                'tipo' => 'cronica',
                'destacada' => $noticia->destacada,
                'cronista_id' => $noticia->cronica ? $noticia->cronica->cronista_id : $noticia->cronista_id,
                'cronista' => $noticia->cronica && $noticia->cronica->cronista ? $noticia->cronica->cronista : $noticia->cronista,
                'archivo_pdf' => $noticia->cronica ? $noticia->cronica->archivo_pdf : $noticia->archivo_pdf,
            ];
        });

    // Combinar FLYERS y CRÓNICASa
    $flyersDocumentos = $flyers->concat($cronicas)
        ->sortByDesc('fecha_publicacion')
        ->values();

    // Obtener sesiones próximas
    $controller = new \App\Http\Controllers\SesionMunicipalController();
    $sesionesProximas = $controller->getSesionesProximas();
    
    // Obtener estadísticas de derecho de palabra
    $derechoController = new \App\Http\Controllers\DerechodePalabraController();
    $estadisticas = $derechoController->getEstadisticas();
    
    // ← AGREGAR ESTAS 2 LÍNEAS
    $comisiones = \App\Models\Comision::all();
    
    return view('welcome', [
        'empresa' => $empresa,
        'noticiaPrincipal' => $noticiaPrincipal,
        'noticiasSecundarias' => $noticiasSecundarias,
        'noticias' => $noticias,
        'flyersDocumentos' => $flyersDocumentos,
        'sesionesProximas' => $sesionesProximas,
        'estadisticas' => $estadisticas,
        'totalOrdenanzas' => \App\Models\Ordenanza::count(),
        'totalGacetas' => \App\Models\Gaceta::count(),
        'totalAcuerdos' => \App\Models\Acuerdo::count(),
        'totalComisiones' => \App\Models\Comision::count(),
        'totalConcejales' => \App\Models\Concejal::count(),
        'logo' => \App\Models\Setting::get('logo'),
        'logoBackgroundSolid' => \App\Models\Setting::get('logo_background_solid'),
        'organigramaSize' => $organigramaSize,
        'comisiones' => $comisiones,  
    ]);
    
})->name('home');
Route::resource('instrumentos_legales', InstrumentosLegalesController::class);
// En tu archivo routes/web.php
Route::get('/noticias', [NoticiaCompletaController::class, 'index'])->name('web.page.noticias.index');
Route::get('/videos', [NoticiaCompletaController::class, 'videos'])->name('web.page.noticias.videos');
Route::get('/noticias/{id}', [NoticiaCompletaController::class, 'show'])->name('web.page.noticias.show');
Route::resource('derecho-palabra', DerechodePalabraController::class, ['only' => ['create', 'store']]);
Route::get('/participacion-ciudadana', [SesionMunicipalController::class, 'index'])->name('web.page.participacion_ciudadana.index');
Route::get('/participacion-ciudadana/estadisticas', [SesionMunicipalController::class, 'show'])->name('web.page.participacion_ciudadana.show');
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/settings/profile', function () {
        return view('livewire.pages.patient.settings.profile');
    })->name('settings.profile');
});
require __DIR__.'/auth.php';