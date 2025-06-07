<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center space-x-4">
                @if($record->profile_image)
                    <img src="{{ Storage::url($record->profile_image) }}" alt="{{ $record->full_name }}" class="h-16 w-16 rounded-full object-cover">
                @else
                    <div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-500 text-lg">{{ strtoupper(substr($record->first_name, 0, 1) . substr($record->last_name, 0, 1)) }}</span>
                    </div>
                @endif
                <div>
                    <h2 class="text-lg font-bold">{{ $record->full_name }}</h2>
                    <div class="text-sm text-gray-500">
                        <span>{{ $record->wins }} Wins</span> · 
                        <span>{{ $record->losses }} Losses</span> · 
                        <span>{{ $record->draws }} Draws</span>
                        @if($record->weight_class)
                            · <span>{{ $record->weight_class }}</span>
                        @endif
                    </div>
                    @if($record->nickname)
                        <div class="text-sm italic">"{{ $record->nickname }}"</div>
                    @endif
                </div>
            </div>
        </div>

        {{ $this->table }}
    </div>
</x-filament-panels::page> 