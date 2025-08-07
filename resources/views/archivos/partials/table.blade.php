<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="divide-y divide-gray-200">
        @forelse($catalogoArchivos as $archivo)
        <div class="p-4 sm:p-6 hover:bg-gray-50 transition-colors duration-200">
            <div class="flex items-start justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-lg bg-[#9d2449]/10 flex items-center justify-center">
                                <svg class="h-5 w-5 text-[#9d2449]" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/>
                                    <polyline points="14,2 14,8 20,8"/>
                                    <line x1="16" y1="13" x2="8" y2="13"/>
                                    <line x1="16" y1="17" x2="8" y2="17"/>
                                    <polyline points="10,9 9,9 8,9"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm sm:text-base font-medium text-gray-900 truncate">
                                {{ $archivo->nombre }}
                            </h3>
                            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                                {{ $archivo->descripcion }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 sm:gap-4 mb-3">
                        <div>
                            <span class="text-xs text-gray-500">Tipo de Persona:</span>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $archivo->tipo_persona }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500">Tipo de Archivo:</span>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ strtoupper($archivo->tipo_archivo) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            @if($archivo->es_visible)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Visible
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293-4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    Oculto
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center space-x-2">
                            <a href="{{ route('archivos.show', $archivo) }}" 
                               class="inline-flex items-center px-2 py-1 text-xs font-medium text-[#9d2449] hover:text-[#8a1f40] transition-colors duration-200">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Ver
                            </a>
                            <a href="{{ route('archivos.edit', $archivo) }}" 
                               class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 hover:text-blue-900 transition-colors duration-200">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Editar
                            </a>
                            <button type="button" 
                                    x-data=""
                                    x-on:click="$dispatch('open-modal', 'confirm-archivo-deletion-{{ $archivo->id }}')"
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-600 hover:text-red-900 transition-colors duration-200">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="p-8 text-center">
            <div class="flex flex-col items-center justify-center text-gray-500">
                <svg class="w-12 h-12 mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-sm sm:text-base md:text-lg lg:text-xl">No hay catálogos de archivos</p>
            </div>
        </div>
        @endforelse
    </div>
</div>
