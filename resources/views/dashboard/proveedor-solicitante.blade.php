@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 p-6">
  <div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-3xl shadow-xl border border-gray-200/50 overflow-hidden">

      <!-- Header -->
      <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 border-b border-gray-200/70">
        <div class="flex justify-between items-center mb-4">
          <div class="flex items-center gap-3">
            <div class="text-2xl font-semibold text-gray-800" id="currentTime">7:09 pm</div>
            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">Sesión Activa</span>
          </div>
        </div>

        <div class="mb-6">
          <h1 class="text-3xl font-bold text-[#9d2449] mb-2">
            Buenas noches, {{ auth()->check() ? strtoupper(auth()->user()->nombre) : 'USUARIO' }}
          </h1>
          <p class="text-gray-600 mb-1">¿Cómo va tu día? 🌞</p>
          <p class="text-gray-600 mb-6">Bienvenido al Padrón de Proveedores del Estado De Oaxaca.</p>

          <a href="{{ route('tramites.index') }}"
             class="inline-block bg-[#9d2449] hover:bg-[#be185d] text-white px-8 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
            Iniciar trámite
          </a>
        </div>
      </div>

      <!-- Contenido -->
      <div class="p-8">
        <div class="flex flex-col lg:flex-row gap-8 items-start">
          <!-- Columna izquierda (contenido principal) -->
          <div class="flex-1 space-y-8">

            <!-- Métricas -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
              <!-- Usuarios -->
              <div class="relative bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200/50">
                <div class="flex items-center mb-3">
                  <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                  </div>
                </div>
                <p class="text-sm text-blue-700 mb-1 font-medium">Usuarios</p>
                <p class="text-2xl font-bold text-blue-900">2,450</p>
                <span class="absolute top-4 right-4 px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-700 font-semibold">+129</span>
              </div>

              <!-- Trámites -->
              <div class="relative bg-gradient-to-br from-amber-50 to-amber-100 rounded-2xl p-6 border border-amber-200/50">
                <div class="flex items-center mb-3">
                  <div class="w-12 h-12 bg-amber-500 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                  </div>
                </div>
                <p class="text-sm text-amber-700 mb-1 font-medium">Trámites</p>
                <p class="text-2xl font-bold text-amber-900">145</p>
                <span class="absolute top-4 right-4 px-2 py-0.5 text-xs rounded-full bg-orange-100 text-orange-700 font-semibold">8 nuevos</span>
              </div>

              <!-- Proveedores -->
              <div class="relative bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-6 border border-green-200/50">
                <div class="flex items-center mb-3">
                  <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                  </div>
                </div>
                <p class="text-sm text-green-700 mb-1 font-medium">Proveedores</p>
                <p class="text-2xl font-bold text-green-900">324</p>
                <span class="absolute top-4 right-4 px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-700 font-semibold">+15%</span>
              </div>

              <!-- Citas -->
              <div class="relative bg-gradient-to-br from-red-50 to-red-100 rounded-2xl p-6 border border-red-200/50">
                <div class="flex items-center mb-3">
                  <div class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                  </div>
                </div>
                <p class="text-sm text-red-700 mb-1 font-medium">Citas</p>
                <p class="text-2xl font-bold text-red-900">89</p>
                <span class="absolute top-4 right-4 px-2 py-0.5 text-xs rounded-full bg-rose-100 text-rose-700 font-semibold">4 hoy</span>
              </div>
            </div>

            <!-- Acciones -->
            <div>
              <h2 class="text-2xl font-bold text-[#9d2449] mb-6">Descubre Proveedores de Oaxaca</h2>
              <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @php
                  $cards = [
                    ['title'=>'Subir Documentos','desc'=>'Carga tus documentos oficiales','icon'=>'M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12'],
                    ['title'=>'Estado de Registro','desc'=>'Consulta tu proceso','icon'=>'M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2m-6 9l2 2 4-4'],
                    ['title'=>'Directorio','desc'=>'Encuentra proveedores locales','icon'=>'M12 6.253v13M3 6.253V19.253C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253M16.5 5c1.747 0 3.332.477 4.5 1.253v13'],
                    ['title'=>'Ayuda','desc'=>'Asistencia con tu registro','icon'=>'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                  ];
                @endphp
                @foreach ($cards as $c)
                  <div class="bg-gradient-to-br from-pink-50 to-rose-100 rounded-2xl p-6 text-center border border-pink-200/50 hover:shadow-lg transition-all duration-300 cursor-pointer group">
                    <div class="w-16 h-16 bg-gradient-to-br from-pink-500 to-rose-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                      <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $c['icon'] }}"/>
                      </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">{{ $c['title'] }}</h3>
                    <p class="text-sm text-gray-600">{{ $c['desc'] }}</p>
                  </div>
                @endforeach
              </div>
            </div>
          </div>

          <!-- Columna derecha (imagen) -->
          <div class="flex-shrink-0 flex justify-center items-start">
            <img src="{{ asset('images/mujer_bienvenida.png') }}"
                 alt="Asistente virtual saludando"
                 class="w-64 h-auto object-contain drop-shadow-lg">
          </div>
        </div>
      </div>
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
