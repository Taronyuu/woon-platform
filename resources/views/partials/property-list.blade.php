@if($properties->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach($properties as $property)
            <a href="{{ route('property.show', $property) }}" class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 group border border-gray-100">
                <div class="relative h-56 overflow-hidden">
                    @if($property->main_image)
                        <img src="{{ $property->main_image }}" alt="{{ $property->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @else
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-400">Geen foto beschikbaar</span>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    @if($property->days_online !== null && $property->days_online <= 7)
                        <div class="absolute top-3 right-3">
                            <span class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-lg">✨ Nieuw</span>
                        </div>
                    @endif
                </div>
                <div class="p-6">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent font-bold text-2xl">
                            {{ $property->formatted_price }}
                        </h3>
                        <button type="button" class="text-gray-400 hover:text-red-500 transition-colors" onclick="event.preventDefault();">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                    </div>
                    <p class="text-gray-700 font-medium mb-2">{{ $property->full_address }}</p>
                    <div class="flex items-center flex-wrap gap-2 text-sm text-gray-600 mb-4">
                        @if($property->property_type_label)
                            <span class="bg-gray-50 px-3 py-1.5 rounded-lg">🏠 {{ $property->property_type_label }}</span>
                        @endif
                        @if($property->bedrooms)
                            <span class="bg-gray-50 px-3 py-1.5 rounded-lg">{{ $property->bedrooms }} kamers</span>
                        @endif
                        @if($property->surface)
                            <span class="bg-gray-50 px-3 py-1.5 rounded-lg">{{ $property->surface }} m²</span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-blue-600 font-semibold group-hover:text-purple-600 transition-colors">Meer info →</span>
                        @if($property->days_online !== null)
                            <span class="text-gray-500">
                                @if($property->days_online === 0) Vandaag
                                @elseif($property->days_online === 1) Gisteren
                                @else {{ $property->days_online }} dagen geleden
                                @endif
                            </span>
                        @endif
                    </div>
                </div>
            </a>
        @endforeach
    </div>
@else
    <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
        <div class="text-6xl mb-4">🔍</div>
        <h2 class="text-2xl font-bold mb-2">Geen woningen gevonden</h2>
        <p class="text-gray-600 mb-6">Probeer je zoekcriteria aan te passen om meer resultaten te vinden</p>
        <a href="{{ route('search') }}" class="inline-block bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition-all">
            Reset filters
        </a>
    </div>
@endif
