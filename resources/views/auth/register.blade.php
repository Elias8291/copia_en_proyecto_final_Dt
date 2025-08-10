@extends('layouts.auth')

@section('title', 'Registro - Padrón de Proveedores de Oaxaca')

@section('content')
    @include('components.ui.modals.loading-modal')
    @include('components.modals.auth.registration-success')
    @include('components.modals.general.error')
    @include('components.ui.modals.sat-data-modal')

    @php
        $mostrarFormulario = old('sat_rfc') || $errors->any();
    @endphp

    <form method="POST" action="{{ route('register') }}" class="space-y-6" enctype="multipart/form-data">
        @csrf
        <div class="text-center mb-3">
            <div class="flex flex-col items-center justify-center mb-2">
                <div class="w-14 h-14 flex items-center justify-center mb-2 bg-gradient-to-br from-primary/10 to-primary-dark/10 rounded-full p-2">
                    <img src="/images/logoprin.jpg" alt="Logo Estado de Oaxaca" class="w-full h-full object-contain rounded-full">
                </div>
                <div class="text-center space-y-1">
                    <span class="text-primary font-bold text-sm block tracking-wide">ADMINISTRACIÓN</span>
                    <span class="text-gray-500 text-xs font-medium uppercase tracking-wider">Gobierno del Estado de Oaxaca</span>
                </div>
            </div>

            <div class="space-y-1 mb-2">
                <h1 class="text-lg font-bold text-gray-800 leading-tight">Registro de Proveedor</h1>
                <p class="text-gray-600 text-xs leading-tight max-w-xs mx-auto">
                    Suba su Constancia de Situación Fiscal con QR
                </p>
            </div>
        </div>

        @if (session('error') || $errors->any())
            <div class="flex justify-center mb-3">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-red-100 border border-red-200 shadow-sm animate-fadeInUp">
                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-xs text-red-700 font-medium">
                        @if (session('error'))
                            {{ session('error') }}
                        @else
                            @if ($errors->has('document'))
                                Formato de archivo no válido o archivo dañado
                            @elseif($errors->has('sat_file'))
                                No se pudo procesar el archivo fiscal
                            @elseif($errors->has('email'))
                                Correo electrónico inválido o ya registrado
                            @elseif($errors->has('password'))
                                La contraseña no cumple con los requisitos
                            @else
                                Por favor, corrija los errores en los campos marcados
                            @endif
                        @endif
                    </span>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="flex justify-center mb-3">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-green-100 border border-green-200 shadow-sm animate-fadeInUp">
                    <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-xs text-green-700 font-medium">
                        {{ session('success') }}
                    </span>
                </div>
            </div>
        @endif

        <div id="uploadArea" class="transition-all duration-300 ease-in-out min-h-[80px] {{ $mostrarFormulario ? 'hidden' : '' }}">
            <div class="mt-1">
                <label for="document" class="block text-xs font-medium text-gray-700 mb-0.5">
                    <span class="block md:inline">Constancia de Situación Fiscal</span>
                    <span class="text-xs text-gray-500 block md:inline md:ml-1">(PDF o Imagen, máx. 5MB)</span>
                </label>
                <div class="relative">
                    <input type="file" id="document" name="document" accept=".pdf,.png,.jpg,.jpeg" required class="hidden" onchange="uploadFile(this)">
                    <label for="document" class="group flex flex-col items-center justify-center w-full h-16 border-2 border-dashed @error('document') border-red-300 bg-red-50/30 @elseif($errors->has('sat_file')) border-red-300 bg-red-50/30 @else border-primary/20 bg-primary-50/30 @enderror hover:border-primary rounded-lg transition-all duration-300 cursor-pointer hover:bg-primary-50">
                        <div class="flex flex-col md:flex-row items-center space-y-0.5 md:space-y-0 md:space-x-2 px-3">
                            <div class="transform group-hover:scale-110 transition-transform duration-300">
                                @error('document')
                                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                @elseif($errors->has('sat_file'))
                                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-primary/70 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                @enderror
                            </div>
                            <div class="text-center md:text-left">
                                <p class="@error('document') text-red-500 @elseif($errors->has('sat_file')) text-red-500 @else text-primary/70 group-hover:text-primary @enderror font-medium text-xs mb-0">
                                    Haga clic para seleccionar archivo
                                </p>
                                <p class="text-xs @error('document') text-red-400 @elseif($errors->has('sat_file')) text-red-400 @else text-gray-500 @enderror" id="fileName">
                                    PDF o Imagen con QR
                                </p>
                            </div>
                        </div>
                    </label>
                </div>

                @error('document')
                    <div class="mt-1">
                        <span class="text-xs text-red-500 font-medium" id="document-error">{{ $message }}</span>
                    </div>
                @enderror
                @error('sat_file')
                    <div class="mt-1">
                        <span class="text-xs text-red-500 font-medium" id="sat-file-error">{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div id="processingStatus" class="hidden mt-3">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <div class="flex items-center justify-center space-x-2">
                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-primary"></div>
                        <span class="text-xs text-primary font-medium">Extrayendo datos fiscales...</span>
                    </div>
                </div>
            </div>
        </div>

        <div id="registrationForm" class="space-y-2 transition-all duration-300 ease-in-out {{ $mostrarFormulario ? '' : 'hidden' }}">
            <input type="hidden" id="qr_url" name="qr_url" value="{{ old('qr_url') }}">
            <input type="hidden" id="sat_rfc" name="sat_rfc" value="{{ old('sat_rfc') }}">
            <input type="hidden" id="sat_nombre" name="sat_nombre" value="{{ old('sat_nombre') }}">
            <input type="hidden" id="sat_tipo_persona" name="sat_tipo_persona" value="{{ old('sat_tipo_persona') }}">
            <input type="hidden" id="sat_email" name="sat_email" value="{{ old('sat_email') }}">

            <div class="mb-3 p-2 bg-gray-50 border border-gray-200 rounded-md">
                <div class="flex items-center space-x-2">
                    <svg class="w-3.5 h-3.5 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-xs text-gray-600">Los datos fiscales se autocompletaron de su constancia</span>
                </div>
            </div>

            <div class="flex justify-end mb-3">
                <button type="button" onclick="showSatDataFromForm()" class="inline-flex items-center space-x-1.5 px-3 py-1.5 text-xs font-medium text-primary bg-white border border-primary rounded-md hover:bg-primary/5 hover:border-primary-dark transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <span>Ver datos completos</span>
                </button>
            </div>

            <div>
                <label for="email" class="block text-xs font-medium text-gray-700 mb-0.5">Correo Electrónico</label>
                <div class="relative">
                    <input type="email" id="email" name="email" required value="{{ old('email') }}" class="w-full px-2.5 py-1.5 rounded-lg border @error('email') border-red-500 bg-red-50/30 @else border-gray-300 @enderror focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors duration-300 text-sm" placeholder="ejemplo@correo.com" aria-describedby="email-error">
                    @error('email')
                        <div class="absolute right-2 top-1/2 -translate-y-1/2">
                            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    @enderror
                </div>
                @error('email')
                    <div class="mt-1">
                        <span class="text-xs text-red-500 font-medium" id="email-error">{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <x-ui.forms.password-input name="password" label="Contraseña" placeholder="••••••••" />
<x-ui.forms.password-input name="password_confirmation" label="Confirmar Contraseña" placeholder="••••••••" />
        </div>

        <div class="space-y-2 pt-3">
            <button type="button" id="actionButton" onclick="handleActionButton()" class="group w-full bg-gradient-to-r from-primary to-primary-dark hover:from-primary-dark hover:to-primary text-white font-semibold py-2.5 px-4 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 relative overflow-hidden text-sm">
                <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative flex items-center justify-center space-x-2">
                    <svg id="actionIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <span id="actionText">{{ $mostrarFormulario ? 'Registrarse' : 'Siguiente' }}</span>
                </div>
            </button>

            <a href="{{ url('/') }}" class="group w-full bg-white hover:bg-gray-50 text-primary hover:text-primary-dark font-semibold py-2.5 px-4 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 border-2 border-primary/20 hover:border-primary/40 relative overflow-hidden inline-flex items-center justify-center text-sm">
                <div class="relative flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>Volver al Inicio</span>
                </div>
            </a>
        </div>

        <div class="text-center mt-3 relative z-10">
            <a href="{{ route('password.request') }}" class="text-gray-500 hover:text-primary text-xs font-medium transition-all duration-200 flex items-center justify-center space-x-2 hover:bg-gray-50 py-1.5 px-3 rounded-lg">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 0121 9z" />
                </svg>
                <span>¿Olvidaste tu contraseña?</span>
            </a>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="/vendor/pdfjs-dist/pdf.min.js"></script>
    <script src="/vendor/pdfjs-dist/pdf.worker.min.js"></script>
    <script src="/vendor/jsQR/jsQR.min.js"></script>
    <script src="/js/sat-qr-extractor/qr-extractor-simple.js"></script>
    <script src="/js/sat-qr-extractor/sat-scraper-simple.js"></script>
    <script src="/js/sat-qr-extractor/constancia-extractor.js"></script>
    <script src="/js/auth/register-handler.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('showSuccessModal'))
                mostrarModalRegistroExitoso();
            @endif
        });
    </script>
@endpush