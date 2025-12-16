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

