<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstrumentosLegalesController;
use App\Http\Controllers\NoticiaCompletaController;
use App\Http\Controllers\SesionMunicipalController;
use App\Http\Controllers\ChabotWebController;
use App\Http\Controllers\NosotrosController;
use App\Http\Controllers\LocalidadController;
use App\Http\Controllers\AtencionCiudadanaController;
use App\Models\Solicitud;

// HOME
Route::get('/', function () {
    $empresa = \App\Models\Empresa::first();

    $noticiaPrincipal = \App\Models\Noticia::with(['cronista', 'cronica'])
        ->where('destacada', true)
        ->latest('fecha_publicacion')
        ->first();

    $noticiasSecundarias = \App\Models\Noticia::with(['cronista', 'cronica'])
        ->where('destacada', true)
        ->latest('fecha_publicacion')
        ->skip(1)
        ->take(3)
        ->get();

    $noticias = \App\Models\Noticia::with(['cronista', 'cronica'])
        ->where('destacada', true)
        ->latest('fecha_publicacion')
        ->get();

    $flyers = \App\Models\Noticia::with(['cronista', 'cronica'])
        ->where('tipo', 'flyer')
        ->latest('fecha_publicacion')
        ->get();

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

    $flyersDocumentos = $flyers->concat($cronicas)
        ->sortByDesc('fecha_publicacion')
        ->values();

    $controller = new SesionMunicipalController();
    $sesionesProximas = $controller->getSesionesDisponibles();
    $estadisticas = $controller->getEstadisticas();

    $atencionesRealizadas = Solicitud::with(['ciudadano', 'tipoSolicitud'])
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();

    $comisiones = \App\Models\Comision::all();
    $tiposSolicitud = \App\Models\TipoSolicitud::where('activo', true)->get();

    return view('welcome', [
        'empresa' => $empresa,
        'noticiaPrincipal' => $noticiaPrincipal,
        'noticiasSecundarias' => $noticiasSecundarias,
        'noticias' => $noticias,
        'flyersDocumentos' => $flyersDocumentos,
        'sesionesProximas' => $sesionesProximas,
        'estadisticas' => $estadisticas,
        'atencionesRealizadas' => $atencionesRealizadas,
        'totalOrdenanzas' => \App\Models\Ordenanza::count(),
        'totalGacetas' => \App\Models\Gaceta::count(),
        'totalAcuerdos' => \App\Models\Acuerdo::count(),
        'totalComisiones' => \App\Models\Comision::count(),
        'totalConcejales' => \App\Models\Concejal::count(),
        'logo' => \App\Models\Setting::get('logo'),
        'logoBackgroundSolid' => \App\Models\Setting::get('logo_background_solid'),
        'comisiones' => $comisiones,
        'tiposSolicitud' => $tiposSolicitud,
    ]);
})->name('home');

// PÁGINAS PRINCIPALES
Route::get('/nosotros', [NosotrosController::class, 'index'])->name('nosotros.index');
Route::get('/localidad', [LocalidadController::class, 'index'])->name('localidad.index');

// INSTRUMENTOS LEGALES
Route::resource('instrumentos_legales', InstrumentosLegalesController::class);

// NOTICIAS
Route::get('/noticias', [NoticiaCompletaController::class, 'index'])->name('web.page.noticias.index');
Route::get('/videos', [NoticiaCompletaController::class, 'videos'])->name('web.page.noticias.videos');
Route::get('/noticias/{id}', [NoticiaCompletaController::class, 'show'])->name('web.page.noticias.show');

// CHATBOT
Route::get('/chatbot', [ChabotWebController::class, 'index'])->name('chatbot.index');
Route::post('/chatbot/send-message', [ChabotWebController::class, 'sendMessage']);

// PARTICIPACIÓN CIUDADANA
Route::prefix('participacion-ciudadana')->name('web.page.participacion_ciudadana.')->group(function () {
    Route::get('/sesiones', [SesionMunicipalController::class, 'index'])->name('index');
    Route::get('/estadisticas', [SesionMunicipalController::class, 'show'])->name('show');
});

// ATENCIÓN CIUDADANA
Route::prefix('atencion-ciudadana')->name('web.page.atencion_ciudadana.')->group(function () {
    Route::get('/nueva-solicitud', [AtencionCiudadanaController::class, 'create'])->name('create');
    Route::post('/guardar', [AtencionCiudadanaController::class, 'store'])->name('store');
});

// RUTAS PROTEGIDAS
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/settings/profile', function () {
        return view('livewire.pages.patient.settings.profile');
    })->name('settings.profile');
});

require __DIR__.'/auth.php';
