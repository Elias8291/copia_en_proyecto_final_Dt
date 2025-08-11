@extends('layouts.app')

@section('content')
    @if (auth()->user()->hasRole(['Proveedor', 'Solicitante']))
        @include('dashboard.proveedor-solicitante')
                                @else
        @include('dashboard.administrador-revisor')
                                        @endif
@endsection
