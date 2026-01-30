@extends('web.layouts.app')
@section('title', 'Instrumentos Legales - PLENARIA')
{{-- Header y secciones opcionales --}}
@section('before_content')
    @include('web.navegation.header')
    @include('web.page.instrumentos_legales.html.section')
    @include('web.page.instrumentos_legales.js.sweetalert')
        @include('web.navegation.footer')

@endsection
@section('backToTop')
    <button id="backToTop" class="fixed bottom-6 right-6 bg-primary text-white p-3 rounded-full shadow-lg hover:bg-blue-800 transition-all duration-300 transform scale-0 z-50">
     <i class="fas fa-chevron-up"></i>
    </button>
@endsection
