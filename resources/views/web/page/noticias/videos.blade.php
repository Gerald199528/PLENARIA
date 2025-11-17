@extends('web.layouts.app')
@section('title', 'Videos - PLENARIA')
@section('before_content')
    @include('web.navegation.header')
    <div class="container mx-auto px-4 py-10">
        <div class="mb-6">
            <a  href="{{ route('home') }}#noticias"
                class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition-all">
                <i class="fas fa-arrow-left mr-2"></i>Atrás
            </a>
        </div>
        {{-- Encabezado --}}
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-blue-600 mb-4">
                <i class="fas fa-video text-red-600 mr-2"></i>Videos
            </h1>
            <div class="w-24 h-1 bg-red-400 mx-auto mb-6"></div>
            <p class="text-gray-600 text-lg">
                Transmisiones en vivo, sesiones del concejo y contenido multimedia
            </p>
        </div>
        {{-- Grid de videos --}}
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($videos as $video)
                <div class="card-hover bg-white rounded-xl overflow-hidden shadow-lg group">
                    <div class="relative h-56 bg-gray-900 flex items-center justify-center overflow-hidden">
                        @if($video->tipo_video === 'url')
                            {{-- Embedar video de URL (YouTube, Vimeo, TikTok, Facebook, Instagram) --}}
                            @if(strpos($video->video_url, 'youtube') !== false || strpos($video->video_url, 'youtu.be') !== false)
                                @php
                                    preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+)/', $video->video_url, $matches);
                                    $videoId = $matches[1] ?? null;
                                @endphp
                                @if($videoId)
                                <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $videoId }}" 
                                    frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen></iframe>
                                @endif
                            @elseif(strpos($video->video_url, 'vimeo') !== false)
                                @php
                                    preg_match('/vimeo\.com\/(\d+)/', $video->video_url, $matches);
                                    $videoId = $matches[1] ?? null;
                                @endphp
                                @if($videoId)
                                <iframe class="w-full h-full" src="https://player.vimeo.com/video/{{ $videoId }}" 
                                    frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                                @endif
                            @elseif(strpos($video->video_url, 'tiktok') !== false)
                                <a href="{{ $video->video_url }}" target="_blank" class="w-full h-full flex items-center justify-center bg-black group-hover:bg-black/80 transition-all">
                                    <div class="text-center">
                                        <div class="bg-red-600 text-white rounded-full p-5 group-hover:scale-110 transition-transform mb-4">
                                            <i class="fas fa-play ml-1 text-2xl"></i>
                                        </div>
                                        <p class="text-white font-semibold">Ver en TikTok</p>
                                        <p class="text-gray-300 text-sm mt-2">Haz clic para abrir</p>
                                    </div>
                                </a>
                            @elseif(strpos($video->video_url, 'facebook') !== false || strpos($video->video_url, 'fb.watch') !== false)
                                <iframe class="w-full h-full" src="https://www.facebook.com/plugins/video.php?href={{ urlencode($video->video_url) }}&show_text=false&width=560&appId=123456789" 
                                    frameborder="0" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture" allowfullscreen></iframe>
                            @elseif(strpos($video->video_url, 'instagram') !== false)
                                <blockquote class="instagram-media w-full h-full" data-instgrm-permalink="{{ $video->video_url }}"></blockquote>
                                <script async src="//www.instagram.com/embed.js"></script>
                            @else
                                {{-- Video genérico con imagen de portada --}}
                                <img src="{{ asset('storage/' . $video->imagen) }}" 
                                    alt="{{ $video->titulo }}" class="w-full h-full object-cover">
                                <button class="absolute inset-0 flex items-center justify-center bg-black/40 group-hover:bg-black/60 transition-all">
                                    <div class="bg-red-600 text-white rounded-full p-5 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-play ml-1 text-2xl"></i>
                                    </div>
                                </button>
                            @endif
                        @elseif($video->tipo_video === 'archivo')
                            {{-- Reproducir video local --}}
                            <video class="w-full h-full object-cover" controls>
                                <source src="{{ asset('storage/' . $video->video_archivo) }}" type="video/mp4">
                                Tu navegador no soporta videos HTML5
                            </video>
                        @else
                            {{-- Fallback: imagen con botón de play --}}
                            <img src="{{ asset('storage/' . $video->imagen) }}" 
                                alt="{{ $video->titulo }}" class="w-full h-full object-cover">
                            <button class="absolute inset-0 flex items-center justify-center bg-black/40 group-hover:bg-black/60 transition-all">
                                <div class="bg-red-600 text-white rounded-full p-5 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-play ml-1 text-2xl"></i>
                                </div>
                            </button>
                        @endif
                        <div class="absolute top-3 right-3 bg-red-600 text-white px-3 py-1 rounded text-xs font-bold">
                            <i class="fas fa-video mr-1"></i>VIDEO
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="text-gray-500 text-xs mb-2">
                            <i class="fas fa-calendar mr-1"></i>{{ $video->fecha_publicacion->format('d M Y') }}
                        </div>
                        <h4 class="font-bold text-blue-600 mb-2 leading-tight">
                            {{ $video->titulo }}
                        </h4>
                        <p class="text-gray-600 text-sm mb-3 leading-relaxed" style="text-align: justify; word-wrap: break-word; overflow-wrap: break-word; text-justify: inter-word; hyphens: auto; line-height: 1.6;">
                            {{ Str::limit($video->contenido, 120) }}
                        </p>
                        <a href="{{ route('web.page.noticias.show', $video->id) }}" 
                           class="text-red-600 text-sm font-semibold hover:underline flex items-center">
                            Ver detalles <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            @empty
                {{-- Mensaje cuando NO hay videos --}}
                <div class="col-span-3 text-center py-16">
                    <div class="bg-gray-100 rounded-full w-32 h-32 flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-video text-gray-400 text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-700 mb-2">No hay videos disponibles</h3>
                    <p class="text-gray-500 mb-6">Pronto publicaremos nuevo contenido multimedia</p>
                </div>
            @endempty
        </div>
    </div>
    <div class="text-center mt-8 mb-8">
      <a href="{{ route('home') }}" 
   class="inline-flex items-center px-6 py-3 text-white font-semibold rounded-lg transition-all hover:scale-105 hover:shadow-lg" style="background: var(--button-color, #4f46e5);">
    <i class="fas fa-home mr-2"></i>Volver al inicio
</a>
    </div>
@endsection