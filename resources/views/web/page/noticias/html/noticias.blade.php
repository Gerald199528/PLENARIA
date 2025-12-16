<!----------- Sección de Noticias Mejorada -------------->
<section id="noticias" class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
      <h2 class="text-4xl lg:text-5xl font-bold text-primary mb-4">Noticias</h2>
<div class="w-24 h-1 mx-auto mb-6" style="background: var(--primary-color, #3b82f6);"></div>
            <p class="text-gray-600 text-lg max-w-3xl mx-auto">
                Manténgase informado sobre las actividades, decisiones y eventos del Concejo Municipal
            </p>
        </div>      
        
        <div class="mb-16">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">
         <i class="fas fa-newspaper text-primary mr-2"></i>Noticias
            </h3>            
            <div class="grid lg:grid-cols-3 gap-8">     
                <!------------------------ COLUMNA IZQUIERDA: Noticia Principal ----------------------------->
                <div class="lg:col-span-2">
                    @if($noticiaPrincipal)
                        <div class="card-hover bg-white rounded-2xl overflow-hidden shadow-lg flex flex-col">
                            <div class="relative h-64 lg:h-80 overflow-hidden flex-shrink-0">
                                <img src="{{ asset('storage/' . $noticiaPrincipal->imagen) }}" 
                                    alt="{{ $noticiaPrincipal->titulo }}" 
                                    class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                                <div class="absolute top-4 left-4 bg-blue-600 text-white px-4 py-2 rounded-full font-semibold">
                                    <i class="fas fa-star mr-2"></i>Destacada
                                </div>
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-6">
                                    <div class="text-white text-sm mb-2">
                                        <i class="fas fa-calendar mr-2"></i>{{ $noticiaPrincipal->fecha_publicacion->format('d \d\e F, Y') }}
                                    </div>
                                    <h3 class="text-white text-2xl font-bold mb-2">
                                        {{ $noticiaPrincipal->titulo }}
                                    </h3>
                                </div>
                            </div>

                            <div class="p-6 flex flex-col flex-grow">
                                <p class="text-gray-600 leading-relaxed mb-4 flex-grow" style="text-align: justify; word-wrap: break-word; overflow-wrap: break-word; text-justify: inter-word; hyphens: auto; line-height: 1.8;">
                                    {!! nl2br(e(Str::limit($noticiaPrincipal->contenido, 500, '...'))) !!}
                                </p>

                                <div class="flex items-center justify-between flex-wrap gap-4 border-t pt-4">
                                    {{-- Botones PDF --}}
                                    <div class="flex items-center gap-2 flex-wrap">
                                        @php
                                            $archivoPDF = $noticiaPrincipal?->archivo_pdf ?? ($noticiaPrincipal?->cronica?->archivo_pdf ?? null);
                                        @endphp

                                        @if($archivoPDF)
                                            <a href="{{ asset('storage/' . $archivoPDF) }}" target="_blank"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold flex items-center gap-2 transition-all text-sm">
                                                <i class="fas fa-eye"></i> Ver PDF
                                            </a>
                                            <a href="{{ asset('storage/' . $archivoPDF) }}" download
                                            class="text-white px-4 py-2 rounded-lg font-semibold flex items-center gap-2 transition-all text-sm" style="background: var(--button-color, #4f46e5); cursor: pointer;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                                                <i class="fas fa-download"></i> Descargar PDF
                                            </a>
                                        @endif
                                    </div>

                                    {{-- Leer más --}}
                                    <a href="{{ route('web.page.noticias.show', $noticiaPrincipal->id) }}" 
                                    class="relative inline-flex items-center px-4 py-2 text-blue-600 font-semibold text-sm overflow-hidden transition-all duration-300 group">
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
                        <div class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl p-12 text-center flex flex-col items-center justify-center min-h-96">
                            <i class="fas fa-newspaper text-gray-400 text-6xl mb-4 block"></i>
                            <h3 class="text-2xl font-bold text-gray-600 mb-2">No hay noticias registradas</h3>
                            <p class="text-gray-500">Actualmente no hay noticias destacadas disponibles. ¡Vuelva pronto para nuevas actualizaciones!</p>
                        </div>
                    @endif
                </div>

                <!-------------COLUMNA DERECHA: NOTICIAS SECUNDARIAS----------------- -->
                <div class="space-y-6">
                    @forelse($noticiasSecundarias as $noticia)
                        <div class="card-hover bg-white rounded-xl overflow-hidden shadow-lg">
                            {{-- Imagen --}}
                            <div class="relative h-40 overflow-hidden">
                                <img src="{{ asset('storage/' . $noticia->imagen) }}" 
                                    alt="{{ $noticia->titulo }}" 
                                    class="w-full h-full object-cover">
                                {{-- Badge de tipo --}}
                                <div class="absolute top-3 left-3 text-white px-3 py-1 rounded-full font-semibold text-xs
                                    @if($noticia->tipo === 'noticia') bg-blue-600
                                    @elseif($noticia->tipo === 'flyer') bg-green-600
                                    @elseif($noticia->tipo === 'cronica') bg-orange-600
                                    @else bg-gray-600
                                    @endif">
                                    {{ ucfirst($noticia->tipo) }}
                                </div>
                            </div>
                            <div class="p-5">
                                <div class="text-gray-500 text-xs mb-2">
                                    <i class="fas fa-calendar mr-1"></i>{{ $noticia->fecha_publicacion->format('d M Y') }}
                                </div>
                                <h4 class="font-bold text-blue-600 mb-3 text-lg leading-tight">
                                    {{ $noticia->titulo }}
                                </h4>
                                <p class="text-gray-600 text-sm leading-relaxed mb-4" style="text-align: justify; word-wrap: break-word; overflow-wrap: break-word; text-justify: inter-word; hyphens: auto;">
                                    {{ Str::limit($noticia->contenido, 150) }}
                                </p>
                                
                                <div class="flex justify-between items-center flex-wrap gap-2">
                                    <a href="{{ route('web.page.noticias.show', $noticia->id) }}" 
                                    class="text-blue-600 font-medium hover:underline flex items-center text-sm">
                                        Leer más <i class="fas fa-arrow-right ml-2"></i>
                                    </a>                                           
                                    @php
                                        $archivoPDF = $noticia->archivo_pdf ?? ($noticia->cronica?->archivo_pdf ?? null);
                                    @endphp

                                    @if($archivoPDF)
                                        <div class="flex gap-2">
                                            <a href="{{ asset('storage/' . $archivoPDF) }}" target="_blank"
                                               class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded-lg font-semibold flex items-center gap-1 transition-all text-xs">
                                                <i class="fas fa-eye"></i> Ver
                                            </a>
                                            <a href="{{ asset('storage/' . $archivoPDF) }}" download
                                               class="bg-white border border-blue-600 text-blue-600 hover:bg-blue-50 px-2 py-1 rounded-lg font-semibold flex items-center gap-1 transition-all text-xs">
                                                <i class="fas fa-download"></i> Descargar
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                  <!-- Sin noticias secundarias -->
                        <div class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl p-8 text-center border border-gray-300">
                            <i class="fas fa-inbox text-gray-400 text-5xl mb-4 block"></i>
                            <h4 class="font-semibold text-gray-600 mb-2">Sin noticias adicionales</h4>
                            <p class="text-gray-500 text-sm">No hay más noticias disponibles en este momento.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <br><br>

        <!------------ SECCIÓN 2: FLYERS Y Cronicas --------------- -->
        <h3 class="text-2xl font-bold text-gray-800 mb-6">
       <i class="fas fa-file-image text-primary mr-2"></i>Flayers & Cronicas
        </h3>
        <div class="mb-16">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($flyersDocumentos->take(6) as $item)
                    <div class="bg-white rounded-2xl overflow-hidden shadow-lg card-hover">
                        @if($item->imagen)
                            <img src="{{ asset('storage/' . $item->imagen) }}" 
                                alt="{{ $item->titulo }}" 
                                class="w-full h-56 object-cover transition-transform duration-500 hover:scale-105">
                        @endif

                        <div class="p-6">
                            <div class="text-sm text-gray-500 mb-2">
                                <i class="fas fa-calendar mr-1"></i>{{ $item->fecha_publicacion->format('d M Y') }} |
                                <span class="text-xs font-bold px-3 py-1 rounded-full
                                    @if(strtolower($item->tipo ?? '') === 'flyer') bg-green-100 text-green-700
                                    @elseif(strtolower($item->tipo ?? '') === 'noticia') bg-blue-100 text-blue-700
                                    @elseif(strtolower($item->tipo ?? '') === 'cronica') bg-orange-100 text-orange-700
                                    @else bg-gray-100 text-gray-700 @endif">
                                    <i class="fas fa-tag mr-1"></i>{{ ucfirst($item->tipo ?? 'Sin tipo') }}
                                </span>
                            </div>

                            <h3 class="font-bold text-xl text-gray-800 mb-2">
                                {{ $item->titulo }}
                            </h3>

                            <p class="text-gray-600 mb-4 text-sm leading-relaxed" style="text-align: justify; word-wrap: break-word; overflow-wrap: break-word; text-justify: inter-word; hyphens: auto; line-height: 1.6;">
                                {{ Str::limit($item->contenido, 120) }}
                            </p>

                            <div class="flex items-center justify-between flex-wrap gap-3">
                                <div class="flex items-center gap-2 flex-wrap">
                                    @if($item->archivo_pdf)
                                        <a href="{{ asset('storage/' . $item->archivo_pdf) }}" 
                                        target="_blank" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg font-semibold text-sm flex items-center gap-2 transition-all">
                                            <i class="fas fa-eye"></i> Ver PDF
                                        </a>
                                    @elseif($item->imagen)
                                        <a href="{{ asset('storage/' . $item->imagen) }}" 
                                        download
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg font-semibold text-sm flex items-center gap-2 transition-all">
                                            <i class="fas fa-download"></i> Descargar
                                        </a>
                                    @endif
                                </div>

                                <a href="{{ route('web.page.noticias.show', $item->id) }}" 
                                class="relative inline-flex items-center px-4 py-2 text-blue-600 font-semibold text-sm overflow-hidden transition-all duration-300 group">
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
           
                    <div class="lg:col-span-3 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl p-12 text-center flex flex-col items-center justify-center min-h-96">
                        <i class="fas fa-image text-gray-400 text-6xl mb-4 block"></i>
                        <h3 class="text-2xl font-bold text-gray-600 mb-2">No hay Flyers ni Crónicas registradas</h3>
                        <p class="text-gray-500">Por el momento no contamos con flyers ni crónicas disponibles. Te invitamos a consultarnos más adelante para descubrir nuestras novedades.</p>
                    </div>
                @endforelse
            </div>
        </div>

<div class="text-center mb-16">
    <a href="{{ route('web.page.noticias.index', ['tipo' => 'noticia']) }}" 
    class="text-white px-8 py-4 rounded-xl font-semibold text-lg flex
     items-center space-x-2 mx-auto max-w-max transition-all duration-300 
     transform hover:scale-105 hover:shadow-lg group " 
     style="background: var(--button-color, #4f46e5);">
        <span>Ver más noticias</span>
        <i class="fas fa-arrow-right transform group-hover:translate-x-1 transition-transform duration-300"></i>
    </a>
</div>
    <!-------------- SECCIÓN 3: VIDEOS ---------------->
        <div class="mb-16">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">
                <i class="fas fa-video text-red-600 mr-2"></i>Videos
            </h3>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($noticias->where('tipo', 'video')->take(3) as $video)
                    <div class="card-hover bg-white rounded-xl overflow-hidden shadow-lg group">
                        <div class="relative h-56 bg-gray-900 flex items-center justify-center overflow-hidden">
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
                                    <a href="{{ $video->video_url }}" target="_blank" class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-500 via-pink-500 to-red-500 group-hover:opacity-80 transition-all">
                                        <div class="text-center">
                                            <div class="bg-white text-pink-600 rounded-full p-5 group-hover:scale-110 transition-transform mb-4">
                                                <i class="fab fa-instagram text-2xl"></i>
                                            </div>
                                            <p class="text-white font-semibold">Ver en Instagram</p>
                                            <p class="text-white/80 text-sm mt-2">Haz clic para abrir</p>
                                        </div>
                                    </a>
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
                                <video class="w-full h-full object-cover" controls>
                                    <source src="{{ asset('storage/' . $video->video_archivo) }}" type="video/mp4">
                                    Tu navegador no soporta videos HTML5
                                </video>
                            @else                      
                                <img src="{{ asset('storage/' . $video->imagen) }}" 
                                    alt="{{ $video->titulo }}" class="w-full h-full object-cover">
                                <button class="absolute inset-0 flex items-center justify-center bg-black/40 group-hover:bg-black/60 transition-all">
                                    <div class="bg-red-600 text-white rounded-full p-5 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-play ml-1 text-2xl"></i>
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
                                    <div class="absolute top-3 right-3 bg-red-600 text-white px-3 py-1 rounded text-xs font-bold flex items-center gap-1">
                                        <i class="fab fa-youtube"></i> YouTube
                                    </div>
                                @elseif($isVimeo)
                                    <div class="absolute top-3 right-3 bg-blue-600 text-white px-3 py-1 rounded text-xs font-bold flex items-center gap-1">
                                        <i class="fab fa-vimeo"></i> Vimeo
                                    </div>
                                @elseif($isTikTok)
                                    <div class="absolute top-3 right-3 bg-black text-white px-3 py-1 rounded text-xs font-bold flex items-center gap-1">
                                        <i class="fab fa-tiktok"></i> TikTok
                                    </div>
                                @elseif($isFacebook)
                                    <div class="absolute top-3 right-3 bg-blue-500 text-white px-3 py-1 rounded text-xs font-bold flex items-center gap-1">
                                        <i class="fab fa-facebook"></i> Facebook
                                    </div>
                                @elseif($isInstagram)
                                    <div class="absolute top-3 right-3 bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 text-white px-3 py-1 rounded text-xs font-bold flex items-center gap-1">
                                        <i class="fab fa-instagram"></i> Instagram
                                    </div>
                                @endif
                            @else
                                {{-- Etiqueta VIDEO solo para archivos locales --}}
                                <div class="absolute top-3 right-3 bg-red-600 text-white px-3 py-1 rounded text-xs font-bold">
                                    <i class="fas fa-video mr-1"></i>VIDEO
                                </div>
                            @endif
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
                <!-- Sin videos -->
                    <div class="lg:col-span-3 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl p-12 text-center border border-gray-300 flex flex-col items-center justify-center min-h-96">
                        <i class="fas fa-film text-gray-400 text-6xl mb-4 block"></i>
                        <h3 class="text-2xl font-bold text-gray-600 mb-2">No hay videos disponibles</h3>
                        <p class="text-gray-500">Actualmente no hay videos publicados. ¡Vuelva pronto para ver nuestro contenido!</p>
                    </div>
                @endforelse
            </div>          
        </div>

<div class="text-center mt-10">
    <a href="{{ route('web.page.noticias.videos', ['tipo' => 'video']) }}" 
    class="text-white px-8 py-4 rounded-xl font-semibold text-lg flex items-center 
    space-x-2 mx-auto max-w-max transition-all duration-300 transform hover:scale-105
     hover:shadow-lg group " style="background: var(--button-color, #4f46e5);">
        <span>Ver más Videos</span>
        <i class="fas fa-arrow-right transform group-hover:translate-x-1 transition-transform duration-300"></i>
    </a>
</div>
    </div>
</section>