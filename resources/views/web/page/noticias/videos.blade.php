@extends('web.layouts.app')
@section('title', 'Videos - PLENARIA')
@section('before_content')
    @include('web.navegation.header')
    <div class="container mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-6 sm:py-8 md:py-10">
        <div class="mb-4 sm:mb-6">
            <a  href="{{ route('home') }}#noticias"
                class="inline-flex items-center px-3 sm:px-4 py-2 sm:py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition-all text-xs sm:text-sm">
                <i class="fas fa-arrow-left mr-1 sm:mr-2"></i>Atrás
            </a>
        </div>
        {{-- Encabezado --}}
        <div class="text-center mb-8 sm:mb-10">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-blue-600 mb-3 sm:mb-4 px-2">
                <i class="fas fa-video text-red-600 mr-1 sm:mr-2"></i>Videos
            </h1>
            <div class="w-16 sm:w-20 md:w-24 h-1 bg-red-400 mx-auto mb-4 sm:mb-6"></div>
            <p class="text-gray-600 text-sm sm:text-base md:text-lg px-2 sm:px-0">
                Transmisiones en vivo, sesiones del concejo y contenido multimedia
            </p>
        </div>
        {{-- Grid de videos --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 md:gap-8">
            @forelse($videos as $video)
                <div class="card-hover bg-white rounded-lg sm:rounded-xl overflow-hidden shadow-lg group">
                    <div class="relative h-40 sm:h-48 md:h-56 bg-gray-900 flex items-center justify-center overflow-hidden">
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
                                    <div class="text-center px-4">
                                        <div class="bg-red-600 text-white rounded-full p-4 sm:p-5 group-hover:scale-110 transition-transform mb-2 sm:mb-4 mx-auto w-fit">
                                            <i class="fas fa-play ml-0.5 sm:ml-1 text-lg sm:text-2xl"></i>
                                        </div>
                                        <p class="text-white font-semibold text-sm sm:text-base">Ver en TikTok</p>
                                        <p class="text-gray-300 text-xs sm:text-sm mt-1 sm:mt-2">Haz clic para abrir</p>
                                    </div>
                                </a>
                            @elseif(strpos($video->video_url, 'facebook') !== false || strpos($video->video_url, 'fb.watch') !== false)
                                <iframe class="w-full h-full" src="https://www.facebook.com/plugins/video.php?href={{ urlencode($video->video_url) }}&show_text=false&width=560&appId=123456789"
                                    frameborder="0" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture" allowfullscreen></iframe>
                            @elseif(strpos($video->video_url, 'instagram') !== false)
                                <a href="{{ $video->video_url }}" target="_blank" class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-500 via-pink-500 to-red-500 group-hover:opacity-80 transition-all">
                                    <div class="text-center px-4">
                                        <div class="bg-white text-pink-600 rounded-full p-4 sm:p-5 group-hover:scale-110 transition-transform mb-2 sm:mb-4 mx-auto w-fit">
                                            <i class="fab fa-instagram text-lg sm:text-2xl"></i>
                                        </div>
                                        <p class="text-white font-semibold text-sm sm:text-base">Ver en Instagram</p>
                                        <p class="text-white/80 text-xs sm:text-sm mt-1 sm:mt-2">Haz clic para abrir</p>
                                    </div>
                                </a>
                            @else
                                {{-- Video genérico con imagen de portada --}}
                                <img src="{{ asset('storage/' . $video->imagen) }}"
                                    alt="{{ $video->titulo }}" class="w-full h-full object-cover">
                                <button class="absolute inset-0 flex items-center justify-center bg-black/40 group-hover:bg-black/60 transition-all">
                                    <div class="bg-red-600 text-white rounded-full p-4 sm:p-5 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-play ml-0.5 sm:ml-1 text-lg sm:text-2xl"></i>
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
                                <div class="bg-red-600 text-white rounded-full p-4 sm:p-5 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-play ml-0.5 sm:ml-1 text-lg sm:text-2xl"></i>
                                </div>
                            </button>
                        @endif

                        {{-- Badge con logo de plataforma o etiqueta VIDEO --}}
                        @php
                            $isYoutube = strpos($video->video_url, 'youtube') !== false || strpos($video->video_url, 'youtu.be') !== false;
                            $isVimeo = strpos($video->video_url, 'vimeo') !== false;
                            $isTikTok = strpos($video->video_url, 'tiktok') !== false;
                            $isFacebook = strpos($video->video_url, 'facebook') !== false || strpos($video->video_url, 'fb.watch') !== false;
                            $isInstagram = strpos($video->video_url, 'instagram') !== false;
                            $isExternalPlatform = ($video->tipo_video === 'url') && ($isYoutube || $isVimeo || $isTikTok || $isFacebook || $isInstagram);
                        @endphp

                        @if($isExternalPlatform)
                            {{-- Logos de plataformas externas --}}
                            @if($isYoutube)
                                <div class="absolute top-2 sm:top-3 right-2 sm:right-3 bg-red-600 text-white px-2 sm:px-3 py-1 rounded text-xs font-bold flex items-center gap-1">
                                    <i class="fab fa-youtube"></i> <span class="hidden sm:inline">YouTube</span>
                                </div>
                            @elseif($isVimeo)
                                <div class="absolute top-2 sm:top-3 right-2 sm:right-3 bg-blue-600 text-white px-2 sm:px-3 py-1 rounded text-xs font-bold flex items-center gap-1">
                                    <i class="fab fa-vimeo"></i> <span class="hidden sm:inline">Vimeo</span>
                                </div>
                            @elseif($isTikTok)
                                <div class="absolute top-2 sm:top-3 right-2 sm:right-3 bg-black text-white px-2 sm:px-3 py-1 rounded text-xs font-bold flex items-center gap-1">
                                    <i class="fab fa-tiktok"></i> <span class="hidden sm:inline">TikTok</span>
                                </div>
                            @elseif($isFacebook)
                                <div class="absolute top-2 sm:top-3 right-2 sm:right-3 bg-blue-500 text-white px-2 sm:px-3 py-1 rounded text-xs font-bold flex items-center gap-1">
                                    <i class="fab fa-facebook"></i> <span class="hidden sm:inline">Facebook</span>
                                </div>
                            @elseif($isInstagram)
                                <div class="absolute top-2 sm:top-3 right-2 sm:right-3 bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 text-white px-2 sm:px-3 py-1 rounded text-xs font-bold flex items-center gap-1">
                                    <i class="fab fa-instagram"></i> <span class="hidden sm:inline">Instagram</span>
                                </div>
                            @endif
                        @else
                            {{-- Etiqueta VIDEO solo para archivos locales --}}
                            <div class="absolute top-2 sm:top-3 right-2 sm:right-3 bg-red-600 text-white px-2 sm:px-3 py-1 rounded text-xs font-bold">
                                <i class="fas fa-video mr-0.5 sm:mr-1"></i><span class="hidden sm:inline">VIDEO</span>
                            </div>
                        @endif
                    </div>
                    <div class="p-3 sm:p-4">
                        <div class="text-gray-500 text-xs mb-1.5 sm:mb-2">
                            <i class="fas fa-calendar mr-1"></i>{{ $video->fecha_publicacion->format('d M Y') }}
                        </div>
                        <h4 class="font-bold text-blue-600 mb-2 leading-tight text-sm sm:text-base line-clamp-2">
                            {{ $video->titulo }}
                        </h4>
                        <p class="text-gray-600 text-xs sm:text-sm mb-2 sm:mb-3 leading-relaxed" style="text-align: justify; word-wrap: break-word; overflow-wrap: break-word; text-justify: inter-word; hyphens: auto; line-height: 1.6;">
                            {{ Str::limit($video->contenido, 120) }}
                        </p>
                        <a href="{{ route('web.page.noticias.show', $video->id) }}"
                           class="text-red-600 text-xs sm:text-sm font-semibold hover:underline flex items-center gap-1">
                            Ver detalles <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @empty
                {{-- Mensaje cuando NO hay videos --}}
                <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-12 sm:py-16">
                    <div class="bg-gray-100 rounded-full w-24 sm:w-32 h-24 sm:h-32 flex items-center justify-center mx-auto mb-4 sm:mb-6">
                        <i class="fas fa-video text-gray-400 text-4xl sm:text-5xl"></i>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-700 mb-1 sm:mb-2 px-2">No hay videos disponibles</h3>
                    <p class="text-gray-500 mb-4 sm:mb-6 text-sm sm:text-base px-2">Pronto publicaremos nuevo contenido multimedia</p>
                </div>
            @endempty
        </div>
    </div>
    <div class="text-center mt-8 sm:mt-10 md:mt-12 mb-6 sm:mb-8 px-2">
        <a href="{{ route('home') }}"
        class="inline-flex items-center px-4 sm:px-6 py-2.5 sm:py-3 text-white font-semibold rounded-lg transition-all hover:scale-105 hover:shadow-lg text-xs sm:text-sm md:text-base w-full sm:w-auto justify-center" style="background: var(--button-color, #4f46e5);">
            <i class="fas fa-home mr-1.5 sm:mr-2"></i>Volver al inicio
        </a>
    </div>
@endsection
