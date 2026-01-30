<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use App\Models\Empresa;
use Illuminate\Http\Request;

class NoticiaCompletaController extends Controller
{
    public function index(Request $request)
    {
        $empresa = Empresa::first();

        // Traer TODAS las noticias EXCEPTO videos (noticia, flyer, crónica)
        $todasLasNoticias = Noticia::whereIn('tipo', ['noticia', 'flyer', 'cronica'])
            ->with('cronica')
            ->orderBy('fecha_publicacion', 'desc')
            ->get();

        // Separar la principal (la primera) y las secundarias (todas las demás)
        $noticiaPrincipal = $todasLasNoticias->first();
        $noticiasSecundarias = $todasLasNoticias->slice(1);

        // Variable para saber si hay noticias totales
        $hayNoticias = $noticiaPrincipal || $noticiasSecundarias->isNotEmpty();

        // Traer videos SOLO para la sección de videos
        $videos = Noticia::where('tipo', 'video')
            ->with('cronica')
            ->orderBy('fecha_publicacion', 'desc')
            ->limit(3)
            ->get();

        // Traer flyers/documentos (sin límite)
        $flyersDocumentos = Noticia::whereIn('tipo', ['flyer', 'cronica'])
            ->with('cronica')
            ->orderBy('fecha_publicacion', 'desc')
            ->get();

        // Para la sección de videos en la vista
        $noticias = Noticia::where('tipo', 'video')
            ->with('cronica')
            ->orderBy('fecha_publicacion', 'desc')
            ->get();

        return view('web.page.noticias.index', compact(
            'empresa',
            'noticiaPrincipal',
            'noticiasSecundarias',
            'hayNoticias',
            'videos',
            'flyersDocumentos',
            'noticias'
        ));
    }

    public function videos(Request $request)
    {
        $empresa = Empresa::first();

        // Traer solo videos ordenados por fecha
        $videos = Noticia::where('tipo', 'video')
            ->with('cronica')
            ->orderBy('fecha_publicacion', 'desc')
            ->get();

        return view('web.page.noticias.videos', compact('empresa', 'videos'));
    }

    public function show($id)
    {
        $empresa = Empresa::first();

        // Cargar la noticia con su relación crónica
        $noticia = Noticia::with('cronica')->findOrFail($id);
        return view('web.page.noticias.show', compact('empresa', 'noticia'));
    }
}
