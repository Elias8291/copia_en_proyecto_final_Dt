<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50/80">
            <tr>
                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                    <div class="flex items-center space-x-1">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l9-5-9-5-9 5 9 5z" />
                        </svg>
                        <span>Rol</span>
                    </div>
                </th>

                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                    <div class="flex items-center space-x-1">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Estado</span>
                    </div>
                </th>
                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                    <div class="flex items-center space-x-1">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Fecha</span>
                    </div>
                </th>
                <th scope="col"
                    class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                    Acciones
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($roles as $role)
                <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                    <!-- Información del Rol -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gradient-to-br from-[#B4325E] to-[#7a1d37] rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 14l9-5-9-5-9 5 9 5z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="text-sm font-medium text-gray-900 truncate">
                                    {{ $role->name }}
                                </div>
                                <div class="text-sm text-gray-500 line-clamp-2">
                                    {{ $role->description ?: 'Sin descripción' }}
                                </div>
                                <div class="text-xs text-gray-400 mt-1">
                                    {{ $role->users_count }} {{ $role->users_count === 1 ? 'usuario' : 'usuarios' }}
                                </div>
                            </div>
                        </div>
                    </td>

                    <!-- Estado -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                            </svg>
                            Activo
                        </span>
                    </td>

                    <!-- Fecha -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div class="flex flex-col">
                            <span class="font-medium">{{ $role->created_at->format('d/m/Y') }}</span>
                            <span class="text-xs text-gray-400">{{ $role->created_at->format('H:i') }}</span>
                        </div>
                    </td>

                    <!-- Acciones -->
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">
                        <div class="flex items-center justify-center space-x-2">
                            <!-- Ver -->
                            <a href="{{ route('roles.edit', $role) }}"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-green-600 hover:text-green-900 hover:bg-green-100 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                title="Ver permisos">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>

                            <!-- Editar -->
                            <a href="{{ route('roles.edit', $role) }}"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-blue-600 hover:text-blue-900 hover:bg-blue-100 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                title="Editar rol">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>

                            <!-- Eliminar -->
                            <form action="{{ route('roles.destroy', $role) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este rol?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-600 hover:text-red-900 hover:bg-red-100 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                    title="Eliminar rol">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr class="empty-row">
                    <td colspan="4" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center space-y-3">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 14l9-5-9-5-9 5 9 5z" />
                            </svg>
                            <div class="text-gray-500 text-sm">
                                <p class="font-medium">No se encontraron roles</p>
                                <p class="mt-1">Ajusta tus filtros o crea un nuevo rol.</p>
                            </div>
                            <a href="{{ route('roles.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-[#B4325E] text-white text-sm font-medium rounded-lg hover:bg-[#93264B] focus:outline-none focus:ring-2 focus:ring-[#B4325E] focus:ring-offset-2 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Crear primer rol
                            </a>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>