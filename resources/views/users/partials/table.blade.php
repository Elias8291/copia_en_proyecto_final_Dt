<!-- Vista móvil -->
<div class="block lg:hidden">
    <div class="divide-y divide-gray-200">
        @forelse($users as $user)
        <div class="user-row p-6 hover:bg-gray-50/50 transition-all duration-200" 
             data-name="{{ strtolower($user->nombre) }}" 
             data-email="{{ strtolower($user->correo) }}" 
             data-rfc="{{ strtolower($user->rfc ?? '') }}" 
             data-status="{{ $user->fecha_verificacion_correo ? 'verified' : 'pending' }}" 
             data-roles="{{ strtolower($user->roles->pluck('name')->implode(' ')) }}">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <div class="flex-shrink-0 h-14 w-14 bg-gradient-to-br from-[#B4325E] to-[#93264B] text-white rounded-2xl shadow-lg flex items-center justify-center font-bold text-lg">
                            {{ strtoupper(substr($user->nombre, 0, 1)) }}
                        </div>
                        @if($user->fecha_verificacion_correo)
                            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 rounded-full border-2 border-white flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900 text-lg">{{ $user->nombre }}</div>
                        <div class="text-sm text-gray-600 flex items-center mt-1">
                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                            {{ $user->correo }}
                        </div>
                        @if($user->rfc)
                        <div class="text-xs text-gray-500 flex items-center mt-1">
                            <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            RFC: {{ $user->rfc }}
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="flex items-center space-x-2">
                    @can('usuarios.edit')
                    <a href="{{ route('users.edit', $user) }}" 
                       class="p-2.5 text-[#B4325E] hover:text-white hover:bg-[#B4325E] rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                    </a>
                    @endcan

                    @if($user->id !== auth()->id())
                        @can('usuarios.destroy')
                        <button type="button"
                                @click="$dispatch('open-modal', 'confirm-user-deletion-{{ $user->id }}')"
                                class="p-2.5 text-red-600 hover:text-white hover:bg-red-600 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                        @endcan
                    @endif
                </div>
            </div>
            
            <!-- Roles y estado -->
            <div class="flex flex-wrap items-center gap-3 mt-4">
                @foreach($user->roles as $role)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    {{ $role->name }}
                </span>
                @endforeach
                
                @if($user->fecha_verificacion_correo)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Verificado
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Pendiente
                    </span>
                @endif
            </div>
        </div>
        @empty
        <div class="p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No hay usuarios</h3>
            <p class="text-gray-500">No se encontraron usuarios que coincidan con los filtros aplicados.</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Vista desktop -->
<div class="hidden md:block">
    <div class="overflow-x-auto">
        <table class="w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-[#B4325E] via-[#93264B] to-[#7a1d37]">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Usuario</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Email</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider hidden lg:table-cell">RFC</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider hidden xl:table-cell">Roles</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Estado</th>
                    <th scope="col" class="px-4 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($users as $user)
                <tr class="user-row hover:bg-gray-50/50 transition-all duration-200 group" 
                    data-name="{{ strtolower($user->nombre) }}" 
                    data-email="{{ strtolower($user->correo) }}" 
                    data-rfc="{{ strtolower($user->rfc ?? '') }}" 
                    data-status="{{ $user->fecha_verificacion_correo ? 'verified' : 'pending' }}" 
                    data-roles="{{ strtolower($user->roles->pluck('name')->implode(' ')) }}">
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="relative flex-shrink-0 h-10 w-10 bg-gradient-to-br from-[#B4325E] to-[#93264B] text-white rounded-lg shadow-md flex items-center justify-center font-bold text-sm group-hover:shadow-lg transition-shadow duration-200">
                                {{ strtoupper(substr($user->nombre, 0, 1)) }}
                                @if($user->fecha_verificacion_correo)
                                    <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 rounded-full border border-white"></div>
                                @endif
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-semibold text-gray-900">{{ $user->nombre }}</div>
                                <div class="text-xs text-gray-500 md:hidden">{{ $user->correo }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap hidden md:table-cell">
                        <div class="text-sm text-gray-900">{{ $user->correo }}</div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap hidden lg:table-cell">
                        <div class="text-sm text-gray-900 font-mono">{{ $user->rfc ?: 'N/A' }}</div>
                    </td>
                    <td class="px-4 py-4 hidden xl:table-cell">
                        <div class="flex flex-wrap gap-1">
                            @forelse($user->roles as $role)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                {{ $role->name }}
                            </span>
                            @empty
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">
                                Sin roles
                            </span>
                            @endforelse
                        </div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        @if($user->fecha_verificacion_correo)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Verificado
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Pendiente
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-center">
                        <div class="flex items-center justify-center space-x-2">
                            @can('usuarios.edit')
                            <a href="{{ route('users.edit', $user) }}" 
                               class="p-1.5 text-[#B4325E] hover:text-white hover:bg-[#B4325E] rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                               title="Editar usuario">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                            </a>
                            @endcan

                            @if($user->id !== auth()->id())
                                @can('usuarios.destroy')
                                <button type="button"
                                        @click="$dispatch('open-modal', 'confirm-user-deletion-{{ $user->id }}')"
                                        class="p-1.5 text-red-600 hover:text-white hover:bg-red-600 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                        title="Eliminar usuario">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                                @endcan
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No hay usuarios</h3>
                        <p class="text-gray-500">No se encontraron usuarios que coincidan con los filtros aplicados.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div> 