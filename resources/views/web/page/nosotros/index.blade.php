@extends('web.layouts.app')
@section('title', ($nosotros->name ?? 'Nosotros') . ' - PLENARIA')

@section('before_content')
    @include('web.navegation.header')

<!-- Sección Sobre Nosotros -->
<section id="nosotros" class="py-12 md:py-16 lg:py-20 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12 lg:gap-16 items-center">
            <div data-aos="fade-right">
                <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-primary mb-4 md:mb-6">Nuestra Misión</h2>
                <div class="w-20 sm:w-24 h-1 bg-accent mb-6 md:mb-8"></div>
                <p class="text-gray-700 text-base sm:text-lg md:text-xl leading-relaxed mb-6 md:mb-8 text-justify">
                    {{ $empresa->mision ?? 'El Concejo Municipal es el órgano encargado de la función legislativa a nivel local.
                    Trabajamos incansablemente para crear, reformar y derogar ordenanzas que promuevan el desarrollo sostenible y
                    mejoren la calidad de vida de nuestros ciudadanos.' }}
                </p>
                <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-primary mb-4 md:mb-6">Nuestra Visión</h2>
                <div class="w-20 sm:w-24 h-1 bg-accent mb-6 md:mb-8"></div>
                <p class="text-gray-700 text-base sm:text-lg md:text-xl leading-relaxed mb-6 md:mb-8 text-justify">
                    {{ $empresa->vision ?? 'Nos comprometemos con la transparencia, la participación ciudadana y
                    la rendición de cuentas, garantizando que cada decisión tomada refleje las necesidades y aspiraciones de nuestra
                    comunidad.' }}
                </p>

                <!-- Misión y Visión Mejoradas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                    <div class="card-hover relative overflow-hidden rounded-xl sm:rounded-2xl shadow-lg group">
                        <div class="absolute inset-0" style="background: linear-gradient(135deg, var(--primary-color, #1d4ed8), var(--secondary-color, #3b82f6));"></div>
                        <div class="relative p-6 sm:p-8 text-white">
                            <div class="w-12 sm:w-14 h-12 sm:h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-3 sm:mb-4">
                                <i class="fas fa-bullseye text-2xl sm:text-3xl"></i>
                            </div>
                            <h4 class="font-bold text-2xl sm:text-4xl mb-2 sm:mb-3">Misión</h4>
                            <p class="text-blue-100 text-xs sm:text-sm leading-relaxed">
                                {{ $empresa->mision ?? 'Legislar con excelencia para el desarrollo integral de
                                nuestro municipio, promoviendo la participación ciudadana y el bienestar colectivo.' }}
                            </p>
                        </div>
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                    </div>

                    <div class="card-hover relative overflow-hidden rounded-xl sm:rounded-2xl shadow-lg group">
                        <div class="absolute inset-0" style="background: linear-gradient(135deg, var(--secondary-color, #3b82f6), var(--primary-color, #1d4ed8));"></div>
                        <div class="relative p-6 sm:p-8 text-white">
                            <div class="w-12 sm:w-14 h-12 sm:h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-3 sm:mb-4">
                                <i class="fas fa-eye text-2xl sm:text-3xl"></i>
                            </div>
                            <h4 class="font-bold text-2xl sm:text-4xl mb-2 sm:mb-3">Visión</h4>
                            <p class="text-green-100 text-xs sm:text-sm leading-relaxed">
                                {{ $empresa->vision ?? 'Ser el referente municipal en legislación transparente y participativa,
                                construyendo un futuro sostenible y próspero para todos.' }}
                            </p>
                        </div>
                        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-16 -mb-16"></div>
                    </div>
                </div>
            </div>

            <!-- Imagen con Stats Cards -->
            <div data-aos="fade-left" data-aos-delay="200">
                <div class="relative pb-24 sm:pb-32 md:pb-40">
                    <img src="{{ $logo ? asset('storage/' . $logo) : 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQLDIm0J1k4oKvzZ0OP_7PPkrW1zHRBowUflA&s' }}"
                        alt="Concejo Municipal"
                        class="rounded-xl sm:rounded-2xl shadow-2xl w-full h-auto">

                    <!-- Stats Cards sobre la imagen -->
                    <div class="absolute -bottom-12 sm:-bottom-16 left-1/2 transform -translate-x-1/2 w-11/12">
                        <div class="grid grid-cols-3 gap-2 sm:gap-4">
                            <div class="bg-white rounded-lg sm:rounded-xl shadow-xl p-3 sm:p-4 text-center card-hover">
                                <i class="fas fa-users text-blue-600 text-xl sm:text-2xl mb-1 sm:mb-2"></i>
                                <div class="text-xl sm:text-2xl font-bold text-primary">100%</div>
                                <div class="text-xs text-gray-600">Compromiso</div>
                            </div>
                            <div class="bg-white rounded-lg sm:rounded-xl shadow-xl p-3 sm:p-4 text-center card-hover">
                                <i class="fas fa-file-alt text-green-600 text-xl sm:text-2xl mb-1 sm:mb-2"></i>
                                <div class="text-xl sm:text-2xl font-bold text-primary">24/7</div>
                                <div class="text-xs text-gray-600">Atención</div>
                            </div>
                            <div class="bg-white rounded-lg sm:rounded-xl shadow-xl p-3 sm:p-4 text-center card-hover">
                                <i class="fas fa-heart text-red-600 text-xl sm:text-2xl mb-1 sm:mb-2"></i>
                                <div class="text-xl sm:text-2xl font-bold text-primary">1</div>
                                <div class="text-xs text-gray-600">Comunidad</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Valores institucionales -->
        <div class="mt-16 sm:mt-24 md:mt-32" data-aos="fade-up">
            <h3 class="text-2xl sm:text-3xl md:text-4xl font-bold text-primary mb-8 md:mb-12 text-center">Nuestros Valores</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 lg:gap-8">
                <div class="text-center card-hover bg-white p-6 sm:p-8 rounded-xl sm:rounded-2xl shadow-lg">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 w-14 sm:w-16 h-14 sm:h-16 rounded-xl sm:rounded-2xl flex items-center justify-center mx-auto mb-3 sm:mb-4 shadow-lg">
                        <i class="fas fa-handshake text-white text-xl sm:text-2xl"></i>
                    </div>
                    <h4 class="font-bold text-primary mb-2 text-lg sm:text-xl">Transparencia</h4>
                    <p class="text-gray-600 text-sm sm:text-base">Gestión abierta y accesible para todos los ciudadanos</p>
                </div>
                <div class="text-center card-hover bg-white p-6 sm:p-8 rounded-xl sm:rounded-2xl shadow-lg">
                    <div class="bg-gradient-to-br from-green-500 to-green-600 w-14 sm:w-16 h-14 sm:h-16 rounded-xl sm:rounded-2xl flex items-center justify-center mx-auto mb-3 sm:mb-4 shadow-lg">
                        <i class="fas fa-users text-white text-xl sm:text-2xl"></i>
                    </div>
                    <h4 class="font-bold text-primary mb-2 text-lg sm:text-xl">Participación</h4>
                    <p class="text-gray-600 text-sm sm:text-base">Involucramos a la ciudadanía en las decisiones municipales</p>
                </div>
                <div class="text-center card-hover bg-white p-6 sm:p-8 rounded-xl sm:rounded-2xl shadow-lg">
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 w-14 sm:w-16 h-14 sm:h-16 rounded-xl sm:rounded-2xl flex items-center justify-center mx-auto mb-3 sm:mb-4 shadow-lg">
                        <i class="fas fa-balance-scale text-white text-xl sm:text-2xl"></i>
                    </div>
                    <h4 class="font-bold text-primary mb-2 text-lg sm:text-xl">Justicia</h4>
                    <p class="text-gray-600 text-sm sm:text-base">Legislación equitativa que beneficie a toda la comunidad</p>
                </div>
                <div class="text-center card-hover bg-white p-6 sm:p-8 rounded-xl sm:rounded-2xl shadow-lg">
                    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 w-14 sm:w-16 h-14 sm:h-16 rounded-xl sm:rounded-2xl flex items-center justify-center mx-auto mb-3 sm:mb-4 shadow-lg">
                        <i class="fas fa-leaf text-white text-xl sm:text-2xl"></i>
                    </div>
                    <h4 class="font-bold text-primary mb-2 text-lg sm:text-xl">Sostenibilidad</h4>
                    <p class="text-gray-600 text-sm sm:text-base">Desarrollo responsable que preserve nuestro entorno</p>
                </div>
            </div>
        </div>

        <!-- Estructura Organizacional -->
        <div class="mt-16 sm:mt-24 md:mt-32" data-aos="fade-up">
            <div class="text-center mb-8 md:mb-12">
                <h3 class="text-2xl sm:text-3xl md:text-4xl font-bold text-primary mb-3 md:mb-4">Estructura Organizacional</h3>
                <p class="text-gray-600 text-sm sm:text-base md:text-lg max-w-2xl mx-auto px-2">Conoce la organización interna del Concejo Municipal y nuestro equipo de trabajo comprometido con el servicio a la comunidad.</p>
            </div>

            <div class="max-w-4xl mx-auto">
                <div class="card-hover bg-gradient-to-br from-blue-50 to-white rounded-xl sm:rounded-2xl shadow-xl overflow-hidden border border-blue-100">
                    <div class="p-6 sm:p-8 md:p-12">
                        <div class="flex flex-col md:flex-row items-center gap-6 md:gap-8">
                            <div class="flex-shrink-0">
                                <div class="bg-gradient-to-br from-primary to-secondary w-20 sm:w-24 md:w-32 h-20 sm:h-24 md:h-32 rounded-xl sm:rounded-2xl flex items-center justify-center shadow-lg">
                                    <i class="fas fa-sitemap text-white text-3xl sm:text-4xl md:text-5xl"></i>
                                </div>
                            </div>

                            <div class="flex-grow text-center md:text-left">
                                <h4 class="text-xl sm:text-2xl md:text-3xl font-bold text-primary mb-2 md:mb-3">Organigrama Institucional</h4>
                                <p class="text-gray-600 text-sm sm:text-base md:text-lg mb-4 md:mb-6">Descarga nuestro organigrama oficial actualizado para conocer la estructura completa del Concejo Municipal y las funciones de cada área.</p>
                                <div class="flex flex-col sm:flex-row gap-3 md:gap-4 justify-center md:justify-start">
                                    @if($empresa && $empresa->organigrama_ruta)
                                        <a href="{{ asset('storage/' . $empresa->organigrama_ruta) }}" download class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 sm:py-3 bg-gradient-to-r from-primary to-secondary text-white font-semibold text-sm sm:text-base rounded-lg sm:rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                                            <i class="fas fa-download mr-2"></i>
                                            Descargar PDF
                                        </a>

                                        <button type="button" onclick="window.open('{{ asset('storage/' . $empresa->organigrama_ruta) }}', '_blank')" class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 sm:py-3 bg-white text-primary font-semibold text-sm sm:text-base rounded-lg sm:rounded-xl border-2 border-primary hover:bg-primary hover:text-white transform hover:-translate-y-1 transition-all duration-300">
                                            <i class="fas fa-eye mr-2"></i>
                                            Ver Online
                                        </button>
                                    @else
                                        <p class="text-gray-500 italic text-sm sm:text-base">No hay organigrama disponible</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 md:mt-8 pt-6 md:pt-8 border-t border-blue-100">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6">
                                <div class="flex flex-col sm:flex-row items-center justify-center sm:justify-start gap-2 sm:gap-3">
                                    <i class="fas fa-calendar-alt text-primary text-lg md:text-xl"></i>
                                    <div class="text-center sm:text-left">
                                        <p class="text-xs text-gray-500">Última actualización</p>
                                        @if($empresa)
                                            <p class="text-sm font-semibold text-gray-700">{{ $empresa->created_at_formatted ?? 'N/A' }}</p>
                                        @else
                                            <p class="text-sm font-semibold text-gray-700">N/A</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row items-center justify-center sm:justify-start gap-2 sm:gap-3">
                                    <i class="fas fa-file-pdf text-red-500 text-lg md:text-xl"></i>
                                    <div class="text-center sm:text-left">
                                        <p class="text-xs text-gray-500">Formato</p>
                                        @if($empresa && $empresa->organigrama_ruta && $organigramaSize)
                                            <p class="text-sm font-semibold text-gray-700">PDF - {{ $organigramaSize }} MB</p>
                                        @else
                                            <p class="text-sm font-semibold text-gray-700">PDF - N/A</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row items-center justify-center sm:justify-start gap-2 sm:gap-3">
                                    <i class="fas fa-check-circle text-success text-lg md:text-xl"></i>
                                    <div class="text-center sm:text-left">
                                        <p class="text-xs text-gray-500">Estado</p>
                                        <p class="text-sm font-semibold text-gray-700">Vigente</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    @include('web.html.localidad')
    @include('web.navegation.footer')
@endsection
