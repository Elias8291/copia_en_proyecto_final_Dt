@props([
    'title' => 'Lista de Datos',
    'description' => 'Administra y revisa los datos',
    'createAction' => null,
    'permissions' => []
])

<div class="bg-gray-50/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-200/50 mb-8">
    <div class="p-6 border-b border-gray-100">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="flex items-center space-x-4">
                <div class="bg-gradient-to-br from-primary via-primary-dark to-primary-light rounded-xl p-2.5 shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg md:text-xl lg:text-2xl font-bold text-gray-800">{{ $title }}</h1>
                    <p class="text-xs md:text-sm text-gray-500 mt-1">{{ $description }}</p>
                </div>
            </div>
            
            @if($createAction && hasPermission($createAction['permission'] ?? null, $permissions))
                <div class="flex flex-col lg:flex-row items-center space-y-3 lg:space-y-0 lg:space-x-3">
                    <a href="{{ $createAction['url'] ?? '#' }}" 
                       class="px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-primary to-primary-dark rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span class="hidden lg:inline">{{ $createAction['label'] ?? 'Nuevo' }}</span>
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@php
function hasPermission($permission, $permissions = []) {
    if (!$permission) return true;
    if (auth()->user() && auth()->user()->can($permission)) return true;
    return in_array($permission, $permissions);
}
@endphp