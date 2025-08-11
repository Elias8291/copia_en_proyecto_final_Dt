¡hecho! aquí tienes la vista Blade completa **sin** las tarjetas de “Usuarios / Trámites / Proveedores / Citas”. Mantiene el resto del diseño: hora, chip “Sesión Activa”, saludo, botón “Iniciar trámite”, “Acciones Rápidas” y la ilustración a la derecha.

> Ajusta la ruta de la imagen si la tienes en otro lugar.

```blade
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 p-6"
     style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23f3f4f6&quot; fill-opacity=&quot;0.3&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
  <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8 items-start">

    <!-- Columna principal -->
    <div class="flex-1">
      <div class="bg-white rounded-3xl shadow-xl border border-gray-200/60 overflow-hidden">

        <!-- Header -->
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 border-b border-gray-200/70">
          <div class="flex justify-between items-center mb-4">
            <div class="flex items-center gap-3">
              <div id="currentTime" class="text-2xl font-semibold text-gray-800">12:25 am</div>
              <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">Sesión Activa</span>
            </div>

            <a href="{{ route('tramites.index') }}"
               class="bg-[#9d2449] hover:bg-[#be185d] text-white px-6 py-2.5 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl">
              Iniciar trámite →
            </a>
          </div>

          <h1 class="text-3xl font-bold text-[#9d2449] mb-2">
            Buenos días, {{ auth()->check() ? strtoupper(auth()->user()->nombre . ' ' . (auth()->user()->apellidos ?? '')) : 'USUARIO' }}
          </h1>
          <p class="text-gray-600">Bienvenido al Padrón de Proveedores del Estado De Oaxaca.</p>
        </div>

        <!-- Contenido -->
        <div class="p-8">

          <!-- Acciones Rápidas + Ilustración -->
          <div class="flex flex-col lg:flex-row gap-8 w-full">

            <!-- Acciones -->
            <div class="lg:w-1/4">
              <div class="bg-white rounded-2xl border border-gray-200/70 p-4" style="height: 400px;">
                <div class="flex items-center gap-2 mb-4">
                  <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                    </svg>
                  </div>
                  <h2 class="text-xl font-bold text-[#9d2449]">Acciones Rápidas</h2>
                </div>

                <div class="space-y-3">
                  <div class="flex items-start gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors cursor-pointer group border border-gray-200/50">
                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                      <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                      </svg>
                    </div>
                    <div class="flex-1">
                      <h3 class="font-semibold text-gray-900 mb-1 text-sm">Subir Documentos</h3>
                      <p class="text-xs text-gray-600">Carga tus documentos oficiales</p>
                    </div>
                  </div>

                  <div class="flex items-start gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors cursor-pointer group border border-gray-200/50">
                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                      <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                      </svg>
                    </div>
                    <div class="flex-1">
                      <h3 class="font-semibold text-gray-900 mb-1 text-sm">Estado de Registro</h3>
                      <p class="text-xs text-gray-600">Consulta tu proceso</p>
                    </div>
                  </div>

                  <div class="flex items-start gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors cursor-pointer group border border-gray-200/50">
                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                      <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm3 2h6v4H7V6zm8 8v2H5v-2h10z" clip-rule="evenodd" />
                      </svg>
                    </div>
                    <div class="flex-1">
                      <h3 class="font-semibold text-gray-900 mb-1 text-sm">Directorio</h3>
                      <p class="text-xs text-gray-600">Encuentra proveedores locales</p>
                    </div>
                  </div>

                  <div class="flex items-start gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors cursor-pointer group border border-gray-200/50">
                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                      <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                      </svg>
                    </div>
                    <div class="flex-1">
                      <h3 class="font-semibold text-gray-900 mb-1 text-sm">Ayuda</h3>
                      <p class="text-xs text-gray-600">Nuestro equipo está aquí para asistirte en tu proceso de registro y resolver tus dudas</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Ilustración -->
            <div class="lg:w-3/4 bg-amber-50 rounded-2xl border border-gray-200/70 p-4 flex items-center justify-center overflow-hidden" style="height: 400px;">
              <img src="{{ asset('images/mujer_bienvenida.png') }}"
                   alt="Asistente virtual saludando"
                   class="w-full h-full object-contain drop-shadow-lg">
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- Imagen lateral (opcional en ≥ lg) -->
    <div class="hidden lg:flex flex-shrink-0 justify-end items-start">
      <img src="{{ asset('images/mujer_bienvenida.png') }}"
           alt="Asistente virtual"
           class="w-80 h-auto object-contain drop-shadow-lg">
    </div>

  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  function updateDateTime() {
    const now = new Date();
    let h = now.getHours();
    const m = String(now.getMinutes()).padStart(2, '0');
    const ampm = h >= 12 ? 'pm' : 'am';
    h = h % 12 || 12;
    document.getElementById('currentTime').textContent = `${h}:${m} ${ampm}`;
  }
  updateDateTime();
  setInterval(updateDateTime, 60000);
});
</script>
@endsection
