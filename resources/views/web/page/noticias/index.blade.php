@extends('web.layouts.app')
@section('title', 'Noticias - PLENARIA')
@section('before_content')
    @include('web.navegation.header')
    <div class="container mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-6 sm:py-8 md:py-10">
        <div class="mb-4 sm:mb-6">
            <a href="{{ route('home') }}#noticias"
                class="inline-flex items-center px-3 sm:px-4 py-2 sm:py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition-all text-xs sm:text-sm">
                <i class="fas fa-arrow-left mr-1 sm:mr-2"></i>Atrás
            </a>
        </div>
        <div class="text-center mb-8 sm:mb-10">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-primary mb-3 sm:mb-4 px-2">
                <i class="fas fa-newspaper text-primary mr-1 sm:mr-2"></i>Noticias
            </h1>
            <div class="w-12 sm:w-16 md:w-24 h-1 mx-auto mb-4 sm:mb-6" style="background: var(--primary-color, #3b82f6);"></div>
            <p class="text-gray-600 text-sm sm:text-base md:text-lg px-2 sm:px-0">
                Manténgase informado sobre las actividades, decisiones y eventos del Concejo Municipal
            </p>
        </div>
        @if($hayNoticias)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 md:gap-8">
                {{-------------- Noticia principal ------------}}
                @if($noticiaPrincipal)
                    <div class="lg:col-span-2 card-hover bg-white rounded-lg sm:rounded-2xl overflow-hidden shadow-lg transform transition duration-500 hover:scale-105">
                        <div class="relative h-40 sm:h-52 md:h-64 overflow-hidden">
                            <img src="{{ asset('storage/' . $noticiaPrincipal->imagen) }}"
                                 alt="{{ $noticiaPrincipal->titulo }}"
                                 class="w-full h-full object-cover">
                            <div class="absolute top-2 sm:top-4 left-2 sm:left-4 bg-blue-600 text-white px-2.5 sm:px-4 py-1.5 sm:py-2 rounded-full font-semibold text-xs sm:text-sm">
                                <i class="fas fa-star mr-1 sm:mr-2"></i>{{ ucfirst($noticiaPrincipal->tipo) }}
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-3 sm:p-6">
                                <div class="text-white text-xs sm:text-sm mb-1 sm:mb-2">
                                    <i class="fas fa-calendar mr-1 sm:mr-2"></i>{{ $noticiaPrincipal->fecha_publicacion->format('d \d\e F, Y') }}
                                </div>
                            </div>
                        </div>
                        <div class="p-4 sm:p-6">
                            <h3 class="text-lg sm:text-2xl font-bold text-blue-600 mb-2 sm:mb-3 line-clamp-2">{{ $noticiaPrincipal->titulo }}</h3>
                            <p class="text-gray-600 mb-3 sm:mb-4 leading-relaxed text-xs sm:text-sm md:text-base" style="text-align: justify; word-wrap: break-word; overflow-wrap: break-word; text-justify: inter-word; hyphens: auto; line-height: 1.8;">{{ Str::limit($noticiaPrincipal->contenido, 150) }}</p>
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center flex-wrap gap-2 sm:gap-3">

                                <a href="{{ route('web.page.noticias.show', $noticiaPrincipal->id) }}"
                                   class="text-blue-600 font-medium hover:underline flex items-center text-xs sm:text-sm md:text-base">
                                    Leer más <i class="fas fa-arrow-right ml-1 sm:ml-2"></i>
                                </a>
                                @php
                                    $archivoPDF = $noticiaPrincipal->archivo_pdf ?? ($noticiaPrincipal->cronica?->archivo_pdf ?? null);
                                @endphp

                                @if($archivoPDF)
                                    <div class="flex gap-1.5 sm:gap-2 w-full sm:w-auto">
                                        <a href="{{ asset('storage/' . $archivoPDF) }}" target="_blank"
                                           class="flex-1 sm:flex-none bg-blue-600 hover:bg-blue-700 text-white px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-lg font-semibold flex items-center justify-center sm:justify-start gap-1 transition-all text-xs">
                                            <i class="fas fa-eye"></i> <span class="hidden sm:inline">Ver PDF</span><span class="sm:hidden">Ver</span>
                                        </a>
                                        <a href="{{ asset('storage/' . $archivoPDF) }}" download
                                           class="flex-1 sm:flex-none bg-white border border-blue-600 text-blue-600 hover:bg-blue-50 px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-lg font-semibold flex items-center justify-center sm:justify-start gap-1 transition-all text-xs">
                                            <i class="fas fa-download"></i> <span class="hidden sm:inline">Descargar</span><span class="sm:hidden">Desc</span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{---------------- Noticias secundarias------------ --}}
                @forelse($noticiasSecundarias as $noticia)
                    <div class="card-hover bg-white rounded-lg sm:rounded-2xl overflow-hidden shadow-lg transform transition duration-500 hover:scale-105">
                        <div class="relative h-36 sm:h-48 overflow-hidden">
                            <img src="{{ asset('storage/' . $noticia->imagen) }}"
                                 alt="{{ $noticia->titulo }}"
                                 class="w-full h-full object-cover">
                            <div class="absolute top-2 sm:top-3 left-2 sm:left-3 text-white px-2 sm:px-3 py-0.5 sm:py-1 rounded-full font-semibold text-xs
                                @if($noticia->tipo === 'noticia') bg-blue-600
                                @elseif($noticia->tipo === 'flyer') bg-green-600
                                @elseif($noticia->tipo === 'cronica') bg-orange-600
                                @else bg-gray-600
                                @endif">
                                @if($noticia->tipo === 'noticia')
                                    <i class="fas fa-newspaper mr-0.5 sm:mr-1"></i>
                                @elseif($noticia->tipo === 'flyer')
                                    <i class="fas fa-file-image mr-0.5 sm:mr-1"></i>
                                @elseif($noticia->tipo === 'cronica')
                                    <i class="fas fa-file-alt mr-0.5 sm:mr-1"></i>
                                @endif
                                <span class="hidden sm:inline">{{ ucfirst($noticia->tipo) }}</span><span class="sm:hidden">{{ substr(ucfirst($noticia->tipo), 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="p-4 sm:p-5">
                            <div class="text-gray-500 text-xs mb-1.5 sm:mb-2">
                                <i class="fas fa-calendar mr-1"></i>{{ $noticia->fecha_publicacion->format('d M Y') }}
                            </div>
                            <h4 class="text-sm sm:text-lg font-bold text-blue-600 mb-2 leading-tight line-clamp-2">{{ $noticia->titulo }}</h4>
                            <p class="text-gray-600 mb-2 sm:mb-3 text-xs sm:text-sm leading-relaxed" style="text-align: justify; word-wrap: break-word; overflow-wrap: break-word; text-justify: inter-word; hyphens: auto; line-height: 1.6;">{{ Str::limit($noticia->contenido, 120) }}</p>
                            <div class="border-t border-gray-200 my-2 sm:my-3"></div>

                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center flex-wrap gap-1.5 sm:gap-2">
                                <a href="{{ route('web.page.noticias.show', $noticia->id) }}"
                                class="text-blue-600 font-medium hover:underline flex items-center text-xs sm:text-sm gap-1">
                                    Leer más <i class="fas fa-arrow-right"></i>
                                </a>
                                @php
                                    $archivoPDF = $noticia->archivo_pdf ?? ($noticia->cronica?->archivo_pdf ?? null);
                                @endphp
                                @if($archivoPDF)
                                    <div class="flex gap-1 sm:gap-2 w-full sm:w-auto">
                                        <a href="{{ asset('storage/' . $archivoPDF) }}" target="_blank"
                                           class="flex-1 sm:flex-none bg-blue-600 hover:bg-blue-700 text-white px-2 sm:px-3 py-1 sm:py-1.5 rounded-lg font-semibold flex items-center justify-center gap-0.5 sm:gap-1 transition-all text-xs">
                                            <i class="fas fa-eye"></i> <span class="hidden sm:inline">Ver</span>
                                        </a>
                                        <a href="{{ asset('storage/' . $archivoPDF) }}" download
                                           class="flex-1 sm:flex-none bg-white border border-blue-600 text-blue-600 hover:bg-blue-50 px-2 sm:px-3 py-1 sm:py-1.5 rounded-lg font-semibold flex items-center justify-center gap-0.5 sm:gap-1 transition-all text-xs">
                                            <i class="fas fa-download"></i> <span class="hidden sm:inline">Descargar</span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        @else
            <div class="grid grid-cols-1 gap-4 sm:gap-6 md:gap-8">
                <div class="col-span-1 text-center py-12 sm:py-16">
                    <div class="bg-gray-100 rounded-full w-24 sm:w-32 h-24 sm:h-32 flex items-center justify-center mx-auto mb-4 sm:mb-6">
                        <i class="fas fa-newspaper text-gray-400 text-4xl sm:text-5xl"></i>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-700 mb-1 sm:mb-2 px-2">No hay noticias disponibles</h3>
                    <p class="text-gray-500 mb-4 sm:mb-6 text-sm sm:text-base px-2">Pronto publicaremos nuevas actualizaciones</p>
                </div>
            </div>
        @endif
    </div>
    <div class="text-center mt-8 sm:mt-10 md:mt-12 mb-6 sm:mb-8 px-2">
        <a href="{{ route('home') }}"
           class="inline-flex items-center px-4 sm:px-6 py-2.5 sm:py-3 text-white font-semibold rounded-lg transition-all hover:scale-105 hover:shadow-lg text-xs sm:text-sm md:text-base w-full sm:w-auto justify-center" style="background: var(--button-color, #4f46e5);">
            <i class="fas fa-home mr-1.5 sm:mr-2"></i>Volver al inicio
        </a>
    </div>
@endsection
