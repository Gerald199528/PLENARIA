@extends('web.layouts.app')

@section('title', 'PLENARIA - Bienvenido')

{{-- Header y secciones opcionales --}}
@section('before_content')
    @include('web.navegation.header')
    @include('web.navegation.section')    
    @include('web.html.nosotros')
    @include('web.page.participacion_ciudadana.html.section')
    @include('web.page.noticias.html.noticias')
    @include('web.html.localidad')
    @include('web.navegation.footer')    
@endsection
@section('backToTop')
<button id="backToTop" class="fixed bottom-6 right-6 text-white p-4 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform scale-0 hover:scale-105 z-50 group" style="background: var(--secondary-color);">
    <i class="fas fa-chevron-up text-lg transform group-hover:-translate-y-1 transition-transform duration-300"></i>
</button>
@endsection
