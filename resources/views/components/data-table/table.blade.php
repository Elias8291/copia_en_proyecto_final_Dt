@props([
    'data' => [],
    'columns' => [],
    'actions' => [],
    'showActions' => true,
    'permissions' => [],
    'title' => ''
])

<div class="bg-gray-50/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-200/50 overflow-hidden">
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-10 py-6 border-b border-gray-200">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center mr-4">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">{{ $title }}</h2>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="hidden lg:table-header-group bg-gray-50">
                <tr>
                    @foreach($columns as $column)
                        <th class="px-10 py-5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            {{ $column['label'] }}
                        </th>
                    @endforeach
                    @if($showActions)
                        <th class="px-10 py-5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            Acciones
                        </th>
                    @endif
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse($data as $item)
                    <!-- Desktop Row -->
                    <tr class="hidden lg:table-row hover:bg-gray-50/50 transition-all duration-200" data-item-id="{{ $item->id ?? '' }}">
                        @foreach($columns as $column)
                            <td class="px-10 py-5">
                                <x-data-table.table-cell :column="$column" :item="$item" />
                            </td>
                        @endforeach
                        
                        @if($showActions)
                            <td class="px-10 py-5">
                                <x-data-table.actions :actions="$actions" :item="$item" :permissions="$permissions" />
                            </td>
                        @endif
                    </tr>

                    <!-- Mobile Card -->
                    <div class="lg:hidden bg-white border border-gray-200 rounded-xl p-6 mb-6 shadow-lg hover:shadow-xl transition-all duration-200" data-item-id="{{ $item->id ?? '' }}">
                        <div class="space-y-3">
                            @foreach($columns as $column)
                                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                                    <div class="flex items-center space-x-3">
                                        <x-data-table.mobile-icon :column="$column" :item="$item" />
                                        <div>
                                            <div class="text-sm font-medium text-gray-700">{{ $column['label'] }}</div>
                                            <x-data-table.table-cell :column="$column" :item="$item" :mobile="true" />
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @if($showActions)
                                <div class="pt-3 border-t border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-700">Acciones:</span>
                                        <x-data-table.actions :actions="$actions" :item="$item" :permissions="$permissions" :is-mobile="true" />
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <tr class="lg:table-row">
                        <td colspan="{{ count($columns) + ($showActions ? 1 : 0) }}" class="px-10 py-20 text-center">
                            <x-data-table.empty-state :create-action="$actions['create'] ?? null" :permissions="$permissions" />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>