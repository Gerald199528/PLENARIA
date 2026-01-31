@extends('web.layouts.app')


@section('title', ($empresa->name ?? 'PLENARIA') . ' - Bienvenido')

{{-- Header y secciones opcionales --}}
@section('before_content')
    @include('web.navegation.header')
    @include('web.navegation.section')
    @include('web.html.enlaces')
    @include('web.page.noticias.html.noticias')
    @include('web.page.participacion_ciudadana.html.section')
    @include('web.navegation.footer')
@endsection

