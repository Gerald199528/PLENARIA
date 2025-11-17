@extends('web.layouts.app')
@section('title', 'Noticias - PLENARIA')
@section('before_content')
    @include('web.navegation.header')
    <div class="container mx-auto px-4 py-10">
        <div class="mb-6">
            <a href="{{ route('home') }}#noticias"
                class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition-all">
                <i class="fas fa-arrow-left mr-2"></i>Atrás
            </a>
        </div>
        <div class="text-center mb-10">
    <h1 class="text-4xl font-bold text-primary mb-4">
    <i class="fas fa-newspaper text-primary mr-2"></i>Noticias
</h1>
<div class="w-24 h-1 mx-auto mb-6" style="background: var(--primary-color, #3b82f6);"></div>
            <p class="text-gray-600 text-lg">
                Manténgase informado sobre las actividades, decisiones y eventos del Concejo Municipal
            </p>
        </div>
        @if($hayNoticias)
            <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                            {{-------------- Noticia principal ------------}}
                @if($noticiaPrincipal)
                    <div class="lg:col-span-2 card-hover bg-white rounded-2xl overflow-hidden shadow-lg transform transition duration-500 hover:scale-105">
                        <div class="relative h-64 overflow-hidden">
                            <img src="{{ asset('storage/' . $noticiaPrincipal->imagen) }}" 
                                 alt="{{ $noticiaPrincipal->titulo }}" 
                                 class="w-full h-full object-cover">
                            <div class="absolute top-4 left-4 bg-blue-600 text-white px-4 py-2 rounded-full font-semibold">
                                <i class="fas fa-star mr-2"></i>{{ ucfirst($noticiaPrincipal->tipo) }}
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-6">
                                <div class="text-white text-sm mb-2">
                                    <i class="fas fa-calendar mr-2"></i>{{ $noticiaPrincipal->fecha_publicacion->format('d \d\e F, Y') }}
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-blue-600 mb-3">{{ $noticiaPrincipal->titulo }}</h3>
                            <p class="text-gray-600 mb-4 leading-relaxed" style="text-align: justify; word-wrap: break-word; overflow-wrap: break-word; text-justify: inter-word; hyphens: auto; line-height: 1.8;">{{ Str::limit($noticiaPrincipal->contenido, 150) }}</p>
                            <div class="flex justify-between items-center flex-wrap gap-2">
                                
                                <a href="{{ route('web.page.noticias.show', $noticiaPrincipal->id) }}" 
                                   class="text-blue-600 font-medium hover:underline flex items-center">
                                    Leer más <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                                @php
                                    $archivoPDF = $noticiaPrincipal->archivo_pdf ?? ($noticiaPrincipal->cronica?->archivo_pdf ?? null);
                                @endphp

                                @if($archivoPDF)
                                    <div class="flex gap-2">
                                        <a href="{{ asset('storage/' . $archivoPDF) }}" target="_blank"
                                           class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg font-semibold flex items-center gap-1 transition-all">
                                            <i class="fas fa-eye"></i> Ver PDF
                                        </a>
                                        <a href="{{ asset('storage/' . $archivoPDF) }}" download
                                           class="bg-white border border-blue-600 text-blue-600 hover:bg-blue-50 px-3 py-2 rounded-lg font-semibold flex items-center gap-1 transition-all">
                                            <i class="fas fa-download"></i> Descargar
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                                           {{---------------- Noticias secundarias------------ --}}
                @forelse($noticiasSecundarias as $noticia)
                    <div class="card-hover bg-white rounded-2xl overflow-hidden shadow-lg transform transition duration-500 hover:scale-105">
                        <div class="relative h-48 overflow-hidden">
                            <img src="{{ asset('storage/' . $noticia->imagen) }}" 
                                 alt="{{ $noticia->titulo }}" 
                                 class="w-full h-full object-cover">              
                            <div class="absolute top-3 left-3 text-white px-3 py-1 rounded-full font-semibold text-xs
                                @if($noticia->tipo === 'noticia') bg-blue-600
                                @elseif($noticia->tipo === 'flyer') bg-green-600
                                @elseif($noticia->tipo === 'cronica') bg-orange-600
                                @else bg-gray-600
                                @endif">
                                @if($noticia->tipo === 'noticia')
                                    <i class="fas fa-newspaper mr-1"></i>
                                @elseif($noticia->tipo === 'flyer')
                                    <i class="fas fa-file-image mr-1"></i>
                                @elseif($noticia->tipo === 'cronica')
                                    <i class="fas fa-file-alt mr-1"></i>
                                @endif
                                {{ ucfirst($noticia->tipo) }}
                            </div>
                        </div>
                        <div class="p-5">
                            <div class="text-gray-500 text-xs mb-2">
                                <i class="fas fa-calendar mr-1"></i>{{ $noticia->fecha_publicacion->format('d M Y') }}
                            </div>
                            <h4 class="text-lg font-bold text-blue-600 mb-2 leading-tight">{{ $noticia->titulo }}</h4>
                            <p class="text-gray-600 mb-3 text-sm leading-relaxed" style="text-align: justify; word-wrap: break-word; overflow-wrap: break-word; text-justify: inter-word; hyphens: auto; line-height: 1.6;">{{ Str::limit($noticia->contenido, 120) }}</p>
                            <div class="border-t border-gray-200 my-3"></div>

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
                @endforelse
            </div>
        @else       
            <div class="grid sm:grid-cols-1 gap-8">
                <div class="col-span-3 text-center py-16">
                    <div class="bg-gray-100 rounded-full w-32 h-32 flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-newspaper text-gray-400 text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-700 mb-2">No hay noticias disponibles</h3>
                    <p class="text-gray-500 mb-6">Pronto publicaremos nuevas actualizaciones</p>
                </div>
            </div>
        @endif
    </div>
    <div class="text-center mt-8 mb-8">
<a href="{{ route('home') }}" 
   class="inline-flex items-center px-6 py-3 text-white font-semibold rounded-lg transition-all hover:scale-105 hover:shadow-lg" style="background: var(--button-color, #4f46e5);">
    <i class="fas fa-home mr-2"></i>Volver al inicio
</a>
    </div>
@endsection