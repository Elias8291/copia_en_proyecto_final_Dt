@extends('layouts.auth')

@section('title', 'Registro - Padrón de Proveedores de Oaxaca')

@push('styles')
<style>
    .form-disabled {
        transition: all 0.3s ease-out;
        filter: grayscale(0.3);
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/constancia-extractor.js') }}"></script>
<script src="{{ asset('js/sat-modal.js') }}"></script>
<script src="{{ asset('js/auth/register-handler.js') }}"></script>
@endpush

@section('content')
<form method="POST" action="{{ route('register') }}" class="space-y-6" enctype="multipart/form-data">
    @csrf
    
    @include('auth.partials.header')
    @include('auth.partials.errors')
    @include('auth.partials.upload-area')
    @include('auth.partials.registration-form')
    @include('auth.partials.action-buttons')
    @include('auth.partials.password-recovery')
</form>
@endsection