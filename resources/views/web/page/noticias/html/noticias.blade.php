<!----------- Sección de Noticias Mejorada ---------->
<section id="noticias" class="py-12 sm:py-16 md:py-20 bg-gray-50">
    <div class="container mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
        <div class="text-center mb-8 sm:mb-12 md:mb-16">
            <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-5xl font-bold text-primary mb-3 sm:mb-4">Noticias</h2>
            <div class="w-16 sm:w-20 md:w-24 h-1 mx-auto mb-4 sm:mb-6" style="background: var(--primary-color, #3b82f6);"></div>
            <p class="text-gray-600 text-base sm:text-lg max-w-3xl mx-auto px-2 sm:px-4">
                Manténgase informado sobre las actividades, decisiones y eventos del Concejo Municipal
            </p>
        </div>

        <div class="mb-12 sm:mb-16">
            <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-6 px-2 sm:px-0">
                <i class="fas fa-newspaper text-primary mr-2"></i>Noticias
            </h3>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
                <!------------------------ COLUMNA IZQUIERDA: Noticia Principal ----------------------------->
                <div class="lg:col-span-2">
                    @if($noticiaPrincipal)
                        <div class="card-hover bg-white rounded-lg sm:rounded-2xl overflow-hidden shadow-lg flex flex-col">
                            <div class="relative h-48 sm:h-64 md:h-80 overflow-hidden flex-shrink-0">
                                <img src="{{ asset('storage/' . $noticiaPrincipal->imagen) }}"
                                    alt="{{ $noticiaPrincipal->titulo }}"
                                    class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                                <div class="absolute top-2 sm:top-4 left-2 sm:left-4 bg-blue-600 text-white px-3 sm:px-4 py-1.5 sm:py-2 rounded-full font-semibold text-xs sm:text-sm">
                                    <i class="fas fa-star mr-1 sm:mr-2"></i>Destacada
                                </div>
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-3 sm:p-6">
                                    <div class="text-white text-xs sm:text-sm mb-1 sm:mb-2">
                                        <i class="fas fa-calendar mr-1 sm:mr-2"></i>{{ $noticiaPrincipal->fecha_publicacion->format('d \d\e F, Y') }}
                                    </div>
                                    <h3 class="text-white text-lg sm:text-2xl font-bold mb-1 sm:mb-2 line-clamp-2 sm:line-clamp-none">
                                        {{ $noticiaPrincipal->titulo }}
                                    </h3>
                                </div>
                            </div>

                            <div class="p-4 sm:p-6 flex flex-col flex-grow">
                                <p class="text-gray-600 text-sm sm:text-base leading-relaxed mb-3 sm:mb-4 flex-grow" style="text-align: justify; word-wrap: break-word; overflow-wrap: break-word; text-justify: inter-word; hyphens: auto; line-height: 1.8;">
                                    {!! nl2br(e(Str::limit($noticiaPrincipal->contenido, 500, '...'))) !!}
                                </p>

                                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 sm:gap-4 border-t pt-3 sm:pt-4">
                                    {{-- Botones PDF --}}
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 w-full sm:w-auto flex-wrap">
                                        @php
                                            $archivoPDF = $noticiaPrincipal?->archivo_pdf ?? ($noticiaPrincipal?->cronica?->archivo_pdf ?? null);
                                        @endphp

                                        @if($archivoPDF)
                                            <a href="{{ asset('storage/' . $archivoPDF) }}" target="_blank"
                                            class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-3 sm:px-4 py-2 rounded-lg font-semibold flex items-center justify-center sm:justify-start gap-2 transition-all text-xs sm:text-sm">
                                                <i class="fas fa-eye"></i> Ver PDF
                                            </a>
                                            <a href="{{ asset('storage/' . $archivoPDF) }}" download
                                            class="w-full sm:w-auto text-white px-3 sm:px-4 py-2 rounded-lg font-semibold flex items-center justify-center sm:justify-start gap-2 transition-all text-xs sm:text-sm" style="background: var(--button-color, #4f46e5); cursor: pointer;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                                                <i class="fas fa-download"></i> Descargar PDF
                                            </a>
                                        @endif
                                    </div>

                                    {{-- Leer más --}}
                                    <a href="{{ route('web.page.noticias.show', $noticiaPrincipal->id) }}"
                                    class="w-full sm:w-auto relative inline-flex items-center justify-center sm:justify-start px-3 sm:px-4 py-2 text-blue-600 font-semibold text-xs sm:text-sm overflow-hidden transition-all duration-300 group">
                                        <span class="absolute inset-0 bg-blue-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                                        <span class="relative z-10 group-hover:text-white transition-colors duration-300 flex items-center gap-2">
                                            Leer más <i class="fas fa-arrow-right transition-transform duration-300 group-hover:translate-x-1"></i>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Sin registros de noticias principales -->
                        <div class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg sm:rounded-2xl p-6 sm:p-12 text-center flex flex-col items-center justify-center min-h-72 sm:min-h-96">
                            <i class="fas fa-newspaper text-gray-400 text-5xl sm:text-6xl mb-3 sm:mb-4 block"></i>
                            <h3 class="text-lg sm:text-2xl font-bold text-gray-600 mb-1 sm:mb-2">No hay noticias registradas</h3>
                            <p class="text-gray-500 text-sm sm:text-base px-2">Actualmente no hay noticias destacadas disponibles. ¡Vuelva pronto para nuevas actualizaciones!</p>
                        </div>
                    @endif
                </div>

                <!-------------COLUMNA DERECHA: NOTICIAS SECUNDARIAS----------------- -->
                <div class="space-y-4 sm:space-y-6">
                    @forelse($noticiasSecundarias as $noticia)
                        <div class="card-hover bg-white rounded-lg sm:rounded-xl overflow-hidden shadow-lg">
                            {{-- Imagen --}}
                            <div class="relative h-36 sm:h-40 overflow-hidden">
                                <img src="{{ asset('storage/' . $noticia->imagen) }}"
                                    alt="{{ $noticia->titulo }}"
                                    class="w-full h-full object-cover">
                                {{-- Badge de tipo --}}
                                <div class="absolute top-2 sm:top-3 left-2 sm:left-3 text-white px-2 sm:px-3 py-1 rounded-full font-semibold text-xs
                                    @if($noticia->tipo === 'noticia') bg-blue-600
                                    @elseif($noticia->tipo === 'flyer') bg-green-600
                                    @elseif($noticia->tipo === 'cronica') bg-orange-600
                                    @else bg-gray-600
                                    @endif">
                                    {{ ucfirst($noticia->tipo) }}
                                </div>
                            </div>
                            <div class="p-4 sm:p-5">
                                <div class="text-gray-500 text-xs mb-2">
                                    <i class="fas fa-calendar mr-1"></i>{{ $noticia->fecha_publicacion->format('d M Y') }}
                                </div>
                                <h4 class="font-bold text-blue-600 mb-2 sm:mb-3 text-sm sm:text-lg leading-tight line-clamp-2">
                                    {{ $noticia->titulo }}
                                </h4>
                                <p class="text-gray-600 text-xs sm:text-sm leading-relaxed mb-3 sm:mb-4" style="text-align: justify; word-wrap: break-word; overflow-wrap: break-word; text-justify: inter-word; hyphens: auto;">
                                    {{ Str::limit($noticia->contenido, 150) }}
                                </p>

                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 flex-wrap">
                                    <a href="{{ route('web.page.noticias.show', $noticia->id) }}"
                                    class="text-blue-600 font-medium hover:underline flex items-center text-xs sm:text-sm">
                                        Leer más <i class="fas fa-arrow-right ml-1 sm:ml-2"></i>
                                    </a>
                                    @php
                                        $archivoPDF = $noticia->archivo_pdf ?? ($noticia->cronica?->archivo_pdf ?? null);
                                    @endphp

                                    @if($archivoPDF)
                                        <div class="flex gap-1 sm:gap-2 w-full sm:w-auto">
                                            <a href="{{ asset('storage/' . $archivoPDF) }}" target="_blank"
                                               class="flex-1 sm:flex-none bg-blue-600 hover:bg-blue-700 text-white px-2 sm:px-3 py-1 rounded-lg font-semibold flex items-center justify-center gap-1 transition-all text-xs">
                                                <i class="fas fa-eye"></i> Ver
                                            </a>
                                            <a href="{{ asset('storage/' . $archivoPDF) }}" download
                                               class="flex-1 sm:flex-none bg-white border border-blue-600 text-blue-600 hover:bg-blue-50 px-2 sm:px-3 py-1 rounded-lg font-semibold flex items-center justify-center gap-1 transition-all text-xs">
                                                <i class="fas fa-download"></i> Descargar
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- Sin noticias secundarias -->
                        <div class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg sm:rounded-2xl p-6 sm:p-8 text-center border border-gray-300">
                            <i class="fas fa-inbox text-gray-400 text-5xl sm:text-5xl mb-3 sm:mb-4 block"></i>
                            <h4 class="font-semibold text-gray-600 mb-1 sm:mb-2 text-sm sm:text-base">Sin noticias adicionales</h4>
                            <p class="text-gray-500 text-xs sm:text-sm">No hay más noticias disponibles en este momento.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <br class="hidden sm:block"><br class="hidden sm:block">

        <!------------ SECCIÓN 2: FLYERS Y Cronicas --------------- -->
        <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-6 px-2 sm:px-0">
            <i class="fas fa-file-image text-primary mr-2"></i>Flayers & Cronicas
        </h3>
        <div class="mb-12 sm:mb-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @forelse($flyersDocumentos->take(6) as $item)
                    <div class="bg-white rounded-lg sm:rounded-2xl overflow-hidden shadow-lg card-hover">
                        @if($item->imagen)
                            <img src="{{ asset('storage/' . $item->imagen) }}"
                                alt="{{ $item->titulo }}"
                                class="w-full h-44 sm:h-56 object-cover transition-transform duration-500 hover:scale-105">
                        @endif

                        <div class="p-4 sm:p-6">
                            <div class="text-xs sm:text-sm text-gray-500 mb-2 flex flex-col sm:flex-row sm:items-center gap-2">
                                <span class="flex items-center">
                                    <i class="fas fa-calendar mr-1"></i>{{ $item->fecha_publicacion->format('d M Y') }}
                                </span>
                                <span class="hidden sm:inline">|</span>
                                <span class="text-xs font-bold px-2 sm:px-3 py-1 rounded-full w-fit
                                    @if(strtolower($item->tipo ?? '') === 'flyer') bg-green-100 text-green-700
                                    @elseif(strtolower($item->tipo ?? '') === 'noticia') bg-blue-100 text-blue-700
                                    @elseif(strtolower($item->tipo ?? '') === 'cronica') bg-orange-100 text-orange-700
                                    @else bg-gray-100 text-gray-700 @endif">
                                    <i class="fas fa-tag mr-1"></i>{{ ucfirst($item->tipo ?? 'Sin tipo') }}
                                </span>
                            </div>

                            <h3 class="font-bold text-lg sm:text-xl text-gray-800 mb-2 line-clamp-2">
                                {{ $item->titulo }}
                            </h3>

                            <p class="text-gray-600 mb-3 sm:mb-4 text-xs sm:text-sm leading-relaxed" style="text-align: justify; word-wrap: break-word; overflow-wrap: break-word; text-justify: inter-word; hyphens: auto; line-height: 1.6;">
                                {{ Str::limit($item->contenido, 120) }}
                            </p>

                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 sm:gap-3">
                                <div class="flex items-center gap-2 flex-wrap w-full sm:w-auto">
                                    @if($item->archivo_pdf)
                                        <a href="{{ asset('storage/' . $item->archivo_pdf) }}"
                                        target="_blank"
                                        class="flex-1 sm:flex-none bg-blue-600 hover:bg-blue-700 text-white px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg font-semibold text-xs sm:text-sm flex items-center justify-center gap-1 sm:gap-2 transition-all">
                                            <i class="fas fa-eye"></i> Ver PDF
                                        </a>
                                    @elseif($item->imagen)
                                        <a href="{{ asset('storage/' . $item->imagen) }}"
                                        download
                                        class="flex-1 sm:flex-none bg-blue-600 hover:bg-blue-700 text-white px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg font-semibold text-xs sm:text-sm flex items-center justify-center gap-1 sm:gap-2 transition-all">
                                            <i class="fas fa-download"></i> Descargar
                                        </a>
                                    @endif
                                </div>

                                <a href="{{ route('web.page.noticias.show', $item->id) }}"
                                class="w-full sm:w-auto relative inline-flex items-center justify-center px-4 py-2 text-blue-600 font-semibold text-xs sm:text-sm overflow-hidden transition-all duration-300 group">
                                    <span class="absolute inset-0 bg-blue-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                                    <span class="relative z-10 group-hover:text-white transition-colors duration-300 flex items-center gap-2">
                                        Leer más <i class="fas fa-arrow-right transition-transform duration-300 group-hover:translate-x-1"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Sin flyers y documentos -->
                    <div class="lg:col-span-3 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg sm:rounded-2xl p-6 sm:p-12 text-center border border-gray-300 flex flex-col items-center justify-center min-h-72 sm:min-h-96">
                        <i class="fas fa-image text-gray-400 text-5xl sm:text-6xl mb-3 sm:mb-4 block"></i>
                        <h3 class="text-lg sm:text-2xl font-bold text-gray-600 mb-1 sm:mb-2">No hay Flyers ni Crónicas registradas</h3>
                        <p class="text-gray-500 text-sm sm:text-base px-2">Por el momento no contamos con flyers ni crónicas disponibles. Te invitamos a consultarnos más adelante para descubrir nuestras novedades.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="text-center mb-8 sm:mb-12 md:mb-16 px-2">
            <a href="{{ route('web.page.noticias.index', ['tipo' => 'noticia']) }}"
            class="text-white px-4 sm:px-6 md:px-8 py-2.5 sm:py-3 md:py-4 rounded-lg sm:rounded-xl font-semibold text-xs sm:text-sm md:text-base lg:text-lg flex items-center justify-center gap-1.5 sm:gap-2 mx-auto max-w-max transition-all duration-300 transform hover:scale-105 hover:shadow-lg group inline-flex w-full sm:w-auto"
             style="background: var(--button-color, #4f46e5);">
                <span>Ver más noticias</span>
                <i class="fas fa-arrow-right text-xs sm:text-sm transform group-hover:translate-x-1 transition-transform duration-300"></i>
            </a>
        </div>

        <!-------------- SECCIÓN 3: VIDEOS ---------------->
        <div class="mb-12 sm:mb-16">
            <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-6 px-2 sm:px-0">
                <i class="fas fa-video text-red-600 mr-2"></i>Videos
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @forelse($noticias->where('tipo', 'video')->take(3) as $video)
                    <div class="card-hover bg-white rounded-lg sm:rounded-xl overflow-hidden shadow-lg group">
                        <div class="relative h-44 sm:h-56 bg-gray-900 flex items-center justify-center overflow-hidden">
                            @if($video->tipo_video === 'url')
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
                                                <i class="fas fa-play ml-0.5 sm:ml-1 text-xl sm:text-2xl"></i>
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
                                                <i class="fab fa-instagram text-xl sm:text-2xl"></i>
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
                                            <i class="fas fa-play ml-0.5 sm:ml-1 text-xl sm:text-2xl"></i>
                                        </div>
                                    </button>
                                @endif
                            @elseif($video->tipo_video === 'archivo')
                                <video class="w-full h-full object-cover" controls>
                                    <source src="{{ asset('storage/' . $video->video_archivo) }}" type="video/mp4">
                                    Tu navegador no soporta videos HTML5
                                </video>
                            @else
                                <img src="{{ asset('storage/' . $video->imagen) }}"
                                    alt="{{ $video->titulo }}" class="w-full h-full object-cover">
                                <button class="absolute inset-0 flex items-center justify-center bg-black/40 group-hover:bg-black/60 transition-all">
                                    <div class="bg-red-600 text-white rounded-full p-4 sm:p-5 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-play ml-0.5 sm:ml-1 text-xl sm:text-2xl"></i>
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
                                        <i class="fab fa-youtube"></i> YouTube
                                    </div>
                                @elseif($isVimeo)
                                    <div class="absolute top-2 sm:top-3 right-2 sm:right-3 bg-blue-600 text-white px-2 sm:px-3 py-1 rounded text-xs font-bold flex items-center gap-1">
                                        <i class="fab fa-vimeo"></i> Vimeo
                                    </div>
                                @elseif($isTikTok)
                                    <div class="absolute top-2 sm:top-3 right-2 sm:right-3 bg-black text-white px-2 sm:px-3 py-1 rounded text-xs font-bold flex items-center gap-1">
                                        <i class="fab fa-tiktok"></i> TikTok
                                    </div>
                                @elseif($isFacebook)
                                    <div class="absolute top-2 sm:top-3 right-2 sm:right-3 bg-blue-500 text-white px-2 sm:px-3 py-1 rounded text-xs font-bold flex items-center gap-1">
                                        <i class="fab fa-facebook"></i> Facebook
                                    </div>
                                @elseif($isInstagram)
                                    <div class="absolute top-2 sm:top-3 right-2 sm:right-3 bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 text-white px-2 sm:px-3 py-1 rounded text-xs font-bold flex items-center gap-1">
                                        <i class="fab fa-instagram"></i> Instagram
                                    </div>
                                @endif
                            @else
                                {{-- Etiqueta VIDEO solo para archivos locales --}}
                                <div class="absolute top-2 sm:top-3 right-2 sm:right-3 bg-red-600 text-white px-2 sm:px-3 py-1 rounded text-xs font-bold">
                                    <i class="fas fa-video mr-1"></i>VIDEO
                                </div>
                            @endif
                        </div>
                        <div class="p-4 sm:p-5">
                            <div class="text-gray-500 text-xs mb-2">
                                <i class="fas fa-calendar mr-1"></i>{{ $video->fecha_publicacion->format('d M Y') }}
                            </div>
                            <h4 class="font-bold text-blue-600 mb-2 leading-tight text-sm sm:text-base line-clamp-2">
                                {{ $video->titulo }}
                            </h4>
                            <p class="text-gray-600 text-xs sm:text-sm mb-3 leading-relaxed" style="text-align: justify; word-wrap: break-word; overflow-wrap: break-word; text-justify: inter-word; hyphens: auto; line-height: 1.6;">
                                {{ Str::limit($video->contenido, 120) }}
                            </p>
                            <a href="{{ route('web.page.noticias.show', $video->id) }}"
                               class="text-red-600 text-xs sm:text-sm font-semibold hover:underline flex items-center">
                                Ver detalles <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <!-- Sin videos -->
                    <div class="lg:col-span-3 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg sm:rounded-2xl p-6 sm:p-12 text-center border border-gray-300 flex flex-col items-center justify-center min-h-72 sm:min-h-96">
                        <i class="fas fa-film text-gray-400 text-5xl sm:text-6xl mb-3 sm:mb-4 block"></i>
                        <h3 class="text-lg sm:text-2xl font-bold text-gray-600 mb-1 sm:mb-2">No hay videos disponibles</h3>
                        <p class="text-gray-500 text-sm sm:text-base px-2">Actualmente no hay videos publicados. ¡Vuelva pronto para ver nuestro contenido!</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="text-center mt-6 sm:mt-8 md:mt-10 px-2">
            <a href="{{ route('web.page.noticias.videos', ['tipo' => 'video']) }}"
            class="text-white px-4 sm:px-6 md:px-8 py-2.5 sm:py-3 md:py-4 rounded-lg sm:rounded-xl font-semibold text-xs sm:text-sm md:text-base lg:text-lg flex items-center justify-center gap-1.5 sm:gap-2 mx-auto max-w-max transition-all duration-300 transform hover:scale-105 hover:shadow-lg group inline-flex w-full sm:w-auto" style="background: var(--button-color, #4f46e5);">
                <span>Ver más Videos</span>
                <i class="fas fa-arrow-right text-xs sm:text-sm transform group-hover:translate-x-1 transition-transform duration-300"></i>
            </a>
        </div>
    </div>
</section>
