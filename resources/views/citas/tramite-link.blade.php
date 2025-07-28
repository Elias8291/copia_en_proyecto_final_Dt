@if($item->id_tramite)
    <a href="{{ route('revision.revisar', ['tramite' => $item->id_tramite, 'tipo' => 'revision-digital']) }}" 
       class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-full hover:bg-blue-200 transition-colors duration-200"
       title="Revisar trámite #{{ $item->id_tramite }}">
        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        #{{ $item->id_tramite }}
    </a>
@else
    <span class="text-gray-400 text-xs">Sin trámite</span>
@endif 