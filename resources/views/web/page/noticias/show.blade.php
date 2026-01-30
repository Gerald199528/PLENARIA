@extends('web.layouts.app')
@section('title', $noticia->titulo . ' - PLENARIA')

@section('before_content')
    @include('web.navegation.header')

    <div class="container mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-6 sm:py-8 md:py-10 max-w-5xl">
        {{-- Botón Atrás --}}
        <div class="mb-6 sm:mb-8">
            <a href="{{ route('web.page.noticias.index') }}"
               class="inline-flex items-center px-3 sm:px-4 py-2 sm:py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-all text-xs sm:text-sm">
                <i class="fas fa-arrow-left mr-1.5 sm:mr-2"></i>Volver a Noticias
            </a>
        </div>

        {{-- Encabezado de la noticia --}}
        <div class="text-center mb-8 sm:mb-10">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-primary leading-tight mb-3 sm:mb-4 px-2">
                {{ $noticia->titulo }}
            </h1>
            <div class="w-12 sm:w-16 md:w-20 h-1 mx-auto mb-3 sm:mb-4" style="background: var(--primary-color, #1d4ed8);"></div>
            <p class="text-gray-500 text-xs sm:text-sm flex justify-center items-center gap-1 sm:gap-2 px-2">
                <i class="fas fa-calendar"></i>
                {{ $noticia->fecha_publicacion->format('d M Y') }}
            </p>
        </div>

        {{-- Imagen principal si existe --}}
        @if($noticia->imagen)
            <div class="mb-6 sm:mb-8 -mx-3 sm:mx-0">
                <img src="{{ asset('storage/' . $noticia->imagen) }}"
                     alt="Imagen de {{ $noticia->titulo }}"
                     class="w-full sm:rounded-xl shadow-md object-cover rounded-lg">
            </div>
        @endif

        {{-- Contenido --}}
        <div class="prose prose-blue max-w-none text-gray-800 leading-relaxed px-2 sm:px-0 text-sm sm:text-base" style="text-align: justify; word-wrap: break-word; overflow-wrap: break-word; text-justify: inter-word; hyphens: auto;">
            {!! nl2br(e($noticia->contenido)) !!}
        </div>

        {{-- Video si la noticia tiene --}}
        @php
            $video = $noticia->getVideo();
        @endphp

        @if($video && ($video->video_url || $video->video_archivo))

            @if($video->tipo_video === 'url' && $video->video_url)
                <div class="my-8 sm:my-10 px-2 sm:px-0">
                    <h3 class="text-xl sm:text-2xl font-bold text-blue-700 mb-4 sm:mb-6">
                        <i class="fas fa-video text-red-600 mr-1.5 sm:mr-2"></i>Video Relacionado
                    </h3>

                    <div class="relative w-full rounded-lg sm:rounded-xl overflow-hidden shadow-lg" style="padding-bottom: 56.25%; height: 0;">
                        @if(strpos($video->video_url, 'youtube') !== false || strpos($video->video_url, 'youtu.be') !== false)
                            @php
                                preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+)/', $video->video_url, $matches);
                                $videoId = $matches[1] ?? null;
                            @endphp
                            @if($videoId)
                                <iframe class="absolute top-0 left-0 w-full h-full"
                                    src="https://www.youtube.com/embed/{{ $videoId }}"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen>
                                </iframe>
                            @endif

                        @elseif(strpos($video->video_url, 'vimeo') !== false)
                            @php
                                preg_match('/vimeo\.com\/(\d+)/', $video->video_url, $matches);
                                $videoId = $matches[1] ?? null;
                            @endphp
                            @if($videoId)
                                <iframe class="absolute top-0 left-0 w-full h-full"
                                    src="https://player.vimeo.com/video/{{ $videoId }}"
                                    frameborder="0"
                                    allow="autoplay; fullscreen; picture-in-picture"
                                    allowfullscreen>
                                </iframe>
                            @endif

                        @elseif(strpos($video->video_url, 'facebook') !== false || strpos($video->video_url, 'fb.watch') !== false)
                            <iframe class="absolute top-0 left-0 w-full h-full"
                                src="https://www.facebook.com/plugins/video.php?href={{ urlencode($video->video_url) }}&show_text=false&width=560"
                                frameborder="0"
                                allow="autoplay; clipboard-write; encrypted-media; picture-in-picture"
                                allowfullscreen>
                            </iframe>

                        @else
                            <div class="absolute top-0 left-0 w-full h-full bg-gray-900 flex items-center justify-center">
                                <p class="text-white text-center px-4">
                                    <i class="fas fa-link text-2xl mb-2"></i><br>
                                    <a href="{{ $video->video_url }}" target="_blank" class="text-blue-400 hover:text-blue-300 underline text-sm sm:text-base">
                                        Ver video externo
                                    </a>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            @elseif($video->tipo_video === 'archivo' && $video->video_archivo)
                <div class="my-8 sm:my-10 px-2 sm:px-0">
                    <h3 class="text-xl sm:text-2xl font-bold text-blue-700 mb-4 sm:mb-6">
                        <i class="fas fa-video text-red-600 mr-1.5 sm:mr-2"></i>Video Relacionado
                    </h3>

                    <video class="w-full rounded-lg sm:rounded-xl shadow-lg" controls>
                        <source src="{{ asset('storage/' . $video->video_archivo) }}" type="video/mp4">
                        Tu navegador no soporta videos HTML5
                    </video>
                </div>
            @endif
        @endif

        {{-- Línea divisoria --}}
        <div class="border-t border-gray-200 my-8 sm:my-10"></div>

        {{-- Archivos PDF si existen --}}
        @php
            $archivoPDF = $noticia->archivo_pdf ?? ($noticia->cronica?->archivo_pdf ?? null);
        @endphp

        @if($archivoPDF)
            <div class="flex flex-col sm:flex-row flex-wrap justify-center gap-3 sm:gap-4 mb-8 sm:mb-10 px-2 sm:px-0">
                <a href="{{ asset('storage/' . $archivoPDF) }}" target="_blank"
                   class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg font-semibold flex items-center justify-center gap-2 transition-all text-xs sm:text-sm shadow-sm">
                    <i class="fas fa-eye"></i> Ver Documento
                </a>
                <a href="{{ asset('storage/' . $archivoPDF) }}" download
                   class="w-full sm:w-auto bg-white border border-blue-600 text-blue-600 hover:bg-blue-50 px-4 sm:px-6 py-2 sm:py-3 rounded-lg font-semibold flex items-center justify-center gap-2 transition-all text-xs sm:text-sm shadow-sm">
                    <i class="fas fa-download"></i> Descargar PDF
                </a>
            </div>
        @endif

        {{-- Sección de compartir mejorada --}}
        <div class="mt-12 sm:mt-16 md:mt-20 px-2 sm:px-0">
            {{-- Línea divisoria con estilo --}}
            <div class="flex items-center gap-2 sm:gap-4 mb-8 sm:mb-12">
                <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                <div class="flex items-center gap-1 sm:gap-2 text-gray-600 font-semibold text-xs sm:text-base whitespace-nowrap">
                    <i class="fas fa-share-alt text-primary text-sm sm:text-base"></i>
                    <span>Comparte esta noticia</span>
                </div>
                <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
            </div>

            {{-- Descripción --}}
            <p class="text-center text-gray-600 mb-6 sm:mb-8 text-xs sm:text-sm md:text-base max-w-2xl mx-auto px-2">
                Ayuda a difundir esta información compartiendo en tus redes sociales favoritas
            </p>

            {{-- Botones de compartir modernos --}}
            <div class="flex justify-center flex-wrap gap-2 sm:gap-3 md:gap-4 mb-6 sm:mb-8">
                {{-- Facebook --}}
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(Request::url()) }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="group relative inline-flex items-center justify-center gap-1.5 sm:gap-2 px-4 sm:px-6 py-2.5 sm:py-3 bg-gradient-to-br from-blue-600 to-blue-700 text-white rounded-full font-semibold text-xs sm:text-sm shadow-lg hover:shadow-2xl transform transition-all duration-300 hover:scale-105 hover:-translate-y-1 overflow-hidden"
                   title="Compartir en Facebook">
                    <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    <i class="fab fa-facebook-f text-base sm:text-lg"></i>
                    <span class="hidden sm:inline">Facebook</span>
                </a>

                {{-- WhatsApp --}}
                <a href="https://wa.me/?text={{ urlencode($noticia->titulo . ' ' . Request::url()) }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="group relative inline-flex items-center justify-center gap-1.5 sm:gap-2 px-4 sm:px-6 py-2.5 sm:py-3 bg-gradient-to-br from-green-500 to-green-600 text-white rounded-full font-semibold text-xs sm:text-sm shadow-lg hover:shadow-2xl transform transition-all duration-300 hover:scale-105 hover:-translate-y-1 overflow-hidden"
                   title="Compartir en WhatsApp">
                    <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    <i class="fab fa-whatsapp text-base sm:text-lg"></i>
                    <span class="hidden sm:inline">WhatsApp</span>
                </a>

                {{-- LinkedIn --}}
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(Request::url()) }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="group relative inline-flex items-center justify-center gap-1.5 sm:gap-2 px-4 sm:px-6 py-2.5 sm:py-3 bg-gradient-to-br from-blue-700 to-blue-800 text-white rounded-full font-semibold text-xs sm:text-sm shadow-lg hover:shadow-2xl transform transition-all duration-300 hover:scale-105 hover:-translate-y-1 overflow-hidden"
                   title="Compartir en LinkedIn">
                    <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    <i class="fab fa-linkedin-in text-base sm:text-lg"></i>
                    <span class="hidden sm:inline">LinkedIn</span>
                </a>

                {{-- Instagram (perfil) --}}
                <a href="https://www.instagram.com/tu_perfil_aqui"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="group relative inline-flex items-center justify-center gap-1.5 sm:gap-2 px-4 sm:px-6 py-2.5 sm:py-3 bg-gradient-to-br from-pink-500 via-red-500 to-yellow-500 text-white rounded-full font-semibold text-xs sm:text-sm shadow-lg hover:shadow-2xl transform transition-all duration-300 hover:scale-105 hover:-translate-y-1 overflow-hidden"
                   title="Seguir en Instagram">
                    <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    <i class="fab fa-instagram text-base sm:text-lg"></i>
                    <span class="hidden sm:inline">Instagram</span>
                </a>

                {{-- Copiar vínculo --}}
                <button id="copyLinkBtn"
                    class="group relative inline-flex items-center justify-center gap-1.5 sm:gap-2 px-4 sm:px-6 py-2.5 sm:py-3 bg-gradient-to-br from-gray-500 to-gray-600 text-white rounded-full font-semibold text-xs sm:text-sm shadow-lg hover:shadow-2xl transform transition-all duration-300 hover:scale-105 hover:-translate-y-1 overflow-hidden"
                    title="Copiar enlace">
                    <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    <i id="copyIcon" class="fas fa-link text-base sm:text-lg transition-transform duration-300"></i>
                    <span class="hidden sm:inline">Copiar</span>

                    {{-- Animación de check --}}
                    <span id="checkIcon" class="absolute inset-0 flex items-center justify-center text-green-300 opacity-0 scale-0 transition-all duration-500">
                        <i class="fas fa-check text-base sm:text-lg"></i>
                    </span>
                </button>

                {{-- Mensaje de confirmación mejorado --}}
                <div id="copyMsg" class="hidden flex items-center justify-center gap-2 text-green-600 bg-green-50 border border-green-200 rounded-full px-3 sm:px-6 py-2 sm:py-3 max-w-sm mx-auto shadow-md animate-in fade-in slide-in-from-top-2 duration-300 text-xs sm:text-sm">
                    <i class="fas fa-check-circle text-sm sm:text-lg"></i>
                    <span class="font-medium">Enlace copiado correctamente</span>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('web.page.noticias.js.script')
