@props([
    'actions' => [],
    'item' => null,
    'permissions' => [],
    'isMobile' => false
])

@php
    $actionClasses = $isMobile ? 'p-2' : 'p-2';
    $containerClasses = $isMobile ? 'flex items-center space-x-2' : 'flex items-center space-x-2';
@endphp

<div class="{{ $containerClasses }}">
    @foreach($actions as $actionKey => $action)
        @if($actionKey !== 'create')
            @php
                $hasPermission = !isset($action['permission']) || in_array($action['permission'], $permissions);
            @endphp
            
            @if($hasPermission)
                @if(isset($action['url']))
                    @if(isset($action['method']) && $action['method'] === 'DELETE')
                        @if(isset($action['modalTitle']) || isset($action['modalMessage']))
                            <form action="{{ route($action['url'], $item->id) }}" method="POST" class="inline" 
                                  id="delete-form{{ $isMobile ? '-mobile' : '' }}-{{ $item->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                        onclick="openDeleteModal('deleteModal{{ $item->id }}', '{{ $item->nombre ?? $item->name ?? 'Elemento' }}', '{{ $action['itemType'] ?? 'elemento' }}')"
                                        class="{{ $actionClasses }} {{ $action['color'] ?? 'text-primary' }} hover:bg-primary/10 rounded-lg transition-all duration-200" 
                                        title="{{ $action['label'] ?? $actionKey }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        {!! $action['icon'] ?? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>' !!}
                                    </svg>
                                </button>
                            </form>
                        @else
                            <form action="{{ route($action['url'], $item->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="{{ $actionClasses }} {{ $action['color'] ?? 'text-primary' }} hover:bg-primary/10 rounded-lg transition-all duration-200" 
                                        title="{{ $action['label'] ?? $actionKey }}"
                                        onclick="return confirm('¿Está seguro de que desea eliminar este elemento?')">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        {!! $action['icon'] ?? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>' !!}
                                    </svg>
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route($action['url'], $item->id) }}" 
                           class="{{ $actionClasses }} {{ $action['color'] ?? 'text-primary' }} hover:bg-primary/10 rounded-lg transition-all duration-200" 
                           title="{{ $action['label'] ?? $actionKey }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! $action['icon'] ?? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>' !!}
                            </svg>
                        </a>
                    @endif
                @else
                    <button class="{{ $actionClasses }} {{ $action['color'] ?? 'text-primary' }} hover:bg-primary/10 rounded-lg transition-all duration-200" 
                            title="{{ $action['label'] ?? $actionKey }}"
                            @if(isset($action['onclick'])) onclick="{{ $action['onclick'] }}" @endif>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {!! $action['icon'] ?? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>' !!}
                        </svg>
                    </button>
                @endif
            @endif
        @endif
    @endforeach
</div>