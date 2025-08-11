@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 p-6">
  <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8 items-start">
    
    <!-- Contenedor principal (izquierda) -->
    <div class="flex-1">
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
        <div class="p-8 space-y-8">
          
          <!-- Métricas -->
          <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @php
              $metrics = [
                ['title'=>'Usuarios','count'=>'2,450','color'=>'blue','badge'=>'+129'],
                ['title'=>'Trámites','count'=>'145','color'=>'amber','badge'=>'8 nuevos'],
                ['title'=>'Proveedores','count'=>'324','color'=>'green','badge'=>'+15%'],
                ['title'=>'Citas','count'=>'89','color'=>'red','badge'=>'4 hoy'],
              ];
            @endphp
            @foreach ($metrics as $m)
              <div class="relative bg-gradient-to-br from-{{ $m['color'] }}-50 to-{{ $m['color'] }}-100 rounded-2xl p-6 border border-{{ $m['color'] }}-200/50">
                <div class="flex items-center mb-3">
                  <div class="w-12 h-12 bg-{{ $m['color'] }}-500 rounded-xl flex items-center justify-center shadow-lg">
                    <!-- Aquí iría el icono de cada métrica -->
                  </div>
                </div>
                <p class="text-sm text-{{ $m['color'] }}-700 mb-1 font-medium">{{ $m['title'] }}</p>
                <p class="text-2xl font-bold text-{{ $m['color'] }}-900">{{ $m['count'] }}</p>
                <span class="absolute top-4 right-4 px-2 py-0.5 text-xs rounded-full bg-{{ $m['color'] }}-100 text-{{ $m['color'] }}-700 font-semibold">{{ $m['badge'] }}</span>
              </div>
            @endforeach
          </div>

          <!-- Acciones -->
          <div>
            <h2 class="text-2xl font-bold text-[#9d2449] mb-6">Descubre Proveedores de Oaxaca</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
              @php
                $cards = [
                  ['title'=>'Subir Documentos','desc'=>'Carga tus documentos oficiales'],
                  ['title'=>'Estado de Registro','desc'=>'Consulta tu proceso'],
                  ['title'=>'Directorio','desc'=>'Encuentra proveedores locales'],
                  ['title'=>'Ayuda','desc'=>'Asistencia con tu registro'],
                ];
              @endphp
              @foreach ($cards as $c)
                <div class="bg-gradient-to-br from-pink-50 to-rose-100 rounded-2xl p-6 text-center border border-pink-200/50 hover:shadow-lg transition-all duration-300 cursor-pointer group">
                  <div class="w-16 h-16 bg-gradient-to-br from-pink-500 to-rose-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <!-- Icono -->
                  </div>
                  <h3 class="font-bold text-gray-900 mb-2">{{ $c['title'] }}</h3>
                  <p class="text-sm text-gray-600">{{ $c['desc'] }}</p>
                </div>
              @endforeach
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- Imagen a la derecha FUERA del contenedor -->
    <div class="flex-shrink-0 flex justify-center lg:justify-end items-start">
      <img src="{{ asset('images/mujer_bienvenida.png') }}"
           alt="Asistente virtual saludando"
           class="w-72 h-auto object-contain drop-shadow-lg">
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
