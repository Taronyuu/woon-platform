@extends('layouts.app')

@section('title', 'Zoekresultaten - Wooon.nl')

@section('content')
    <div class="container mx-auto px-4 py-8" x-data="propertyFilters()" x-init="init()">

        <div class="bg-white/95 backdrop-blur-lg rounded-2xl p-6 shadow-xl mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input
                    type="text"
                    x-model.debounce.500ms="filters.search"
                    @input="applyFilters()"
                    placeholder="Plaats of postcode"
                    class="px-5 py-4 border-2 border-gray-200 rounded-xl text-gray-800 focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition-all">
                <select
                    x-model="filters.type"
                    @change="applyFilters()"
                    class="px-5 py-4 border-2 border-gray-200 rounded-xl text-gray-800 focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition-all">
                    <option value="">Alle types</option>
                    <option value="sale">Koop</option>
                    <option value="rent">Huur</option>
                </select>
                <div class="grid grid-cols-2 gap-2">
                    <input
                        type="number"
                        x-model="filters.min_price"
                        @input.debounce.500ms="applyFilters()"
                        placeholder="Min prijs"
                        class="px-5 py-4 border-2 border-gray-200 rounded-xl text-gray-800 focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition-all">
                    <input
                        type="number"
                        x-model="filters.max_price"
                        @input.debounce.500ms="applyFilters()"
                        placeholder="Max prijs"
                        class="px-5 py-4 border-2 border-gray-200 rounded-xl text-gray-800 focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition-all">
                </div>
            </div>
        </div>

        <div x-show="activeFilterCount > 0" x-transition class="mb-4">
            <div class="bg-white rounded-xl p-4 shadow-md flex flex-wrap gap-2 items-center">
                <span class="text-sm font-medium text-gray-700">Actieve filters:</span>
                <template x-for="(label, key) in activeFilterLabels" :key="key">
                    <button
                        @click="clearFilter(key)"
                        class="inline-flex items-center gap-2 bg-blue-100 text-blue-700 px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-blue-200 transition-colors">
                        <span x-text="label"></span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </template>
                <button
                    @click="clearAllFilters()"
                    class="ml-auto text-sm text-gray-600 hover:text-gray-900 font-medium">
                    Alles wissen
                </button>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-6">

            <button
                @click="showMobileFilters = !showMobileFilters"
                class="md:hidden w-full bg-white rounded-xl shadow-lg p-4 flex items-center justify-between font-semibold text-gray-700">
                <span>Filters</span>
                <span x-show="activeFilterCount > 0" class="bg-blue-600 text-white px-2.5 py-1 rounded-full text-xs" x-text="activeFilterCount"></span>
            </button>

            <aside
                class="w-full md:w-64 flex-shrink-0"
                x-show="showMobileFilters || window.innerWidth >= 768"
                x-transition
                @resize.window="if (window.innerWidth >= 768) showMobileFilters = false">
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h3 class="font-bold text-lg mb-4 bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Filters</h3>

                    <div class="mb-6 pb-6 border-b border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Oppervlakte</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input
                                type="number"
                                x-model="filters.min_surface"
                                @input.debounce.500ms="applyFilters()"
                                placeholder="Min m²"
                                class="px-3 py-2 border-2 border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition-all">
                            <input
                                type="number"
                                x-model="filters.max_surface"
                                @input.debounce.500ms="applyFilters()"
                                placeholder="Max m²"
                                class="px-3 py-2 border-2 border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition-all">
                        </div>
                    </div>

                    <div class="mb-6 pb-6 border-b border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Aantal kamers (minimaal)</label>
                        <select
                            x-model="filters.rooms"
                            @change="applyFilters()"
                            class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition-all">
                            <option value="">Maakt niet uit</option>
                            <option value="1">1+ kamers</option>
                            <option value="2">2+ kamers</option>
                            <option value="3">3+ kamers</option>
                            <option value="4">4+ kamers</option>
                            <option value="5">5+ kamers</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Energielabel</label>
                        <select
                            x-model="filters.energy_label"
                            @change="applyFilters()"
                            class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition-all">
                            <option value="">Alle labels</option>
                            <option value="A++++">A++++</option>
                            <option value="A+++">A+++</option>
                            <option value="A++">A++</option>
                            <option value="A+">A+</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                        </select>
                    </div>

                    <button
                        @click="clearAllFilters()"
                        class="w-full text-center text-gray-600 px-4 py-2 rounded-xl border-2 border-gray-200 hover:bg-gray-50 transition-all">
                        Reset filters
                    </button>
                </div>
            </aside>

            <main class="flex-1">
                <div class="mb-6 flex items-center justify-between">
                    <h1 class="text-2xl font-bold">
                        <span x-text="total"></span> woningen gevonden
                    </h1>
                    <select
                        x-model="filters.sort"
                        @change="applyFilters()"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="created_at">Nieuwste eerst</option>
                        <option value="buyprice">Prijs: laag naar hoog</option>
                        <option value="rentprice_month">Huur: laag naar hoog</option>
                        <option value="surface">Oppervlakte: klein naar groot</option>
                    </select>
                </div>

                <div x-show="loading" class="text-center py-12">
                    <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-blue-600 border-t-transparent"></div>
                    <p class="mt-4 text-gray-600">Laden...</p>
                </div>

                <div x-show="!loading" x-html="resultsHtml"></div>

                <div x-show="!loading" class="mt-8" x-html="paginationHtml"></div>
            </main>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function propertyFilters() {
            return {
                filters: {
                    search: '{{ $filters['search'] ?? '' }}',
                    type: '{{ $filters['type'] ?? '' }}',
                    min_price: '{{ $filters['min_price'] ?? '' }}',
                    max_price: '{{ $filters['max_price'] ?? '' }}',
                    min_surface: '{{ $filters['min_surface'] ?? '' }}',
                    max_surface: '{{ $filters['max_surface'] ?? '' }}',
                    rooms: '{{ $filters['rooms'] ?? '' }}',
                    energy_label: '{{ $filters['energy_label'] ?? '' }}',
                    sort: '{{ $filters['sort'] ?? 'created_at' }}',
                    order: '{{ $filters['order'] ?? 'desc' }}'
                },
                loading: false,
                showMobileFilters: false,
                resultsHtml: `@include('partials.property-list', ['properties' => $properties])`,
                paginationHtml: `@include('partials.pagination', ['properties' => $properties])`,
                total: {{ $properties->total() }},

                init() {
                    window.addEventListener('popstate', () => {
                        this.loadFromURL();
                        this.fetchResults();
                    });
                },

                get activeFilterCount() {
                    let count = 0;
                    Object.keys(this.filters).forEach(key => {
                        if (key !== 'sort' && key !== 'order' && this.filters[key] !== '') {
                            count++;
                        }
                    });
                    return count;
                },

                get activeFilterLabels() {
                    const labels = {};
                    const labelMap = {
                        search: 'Zoekterm',
                        type: 'Type',
                        min_price: 'Min prijs',
                        max_price: 'Max prijs',
                        min_surface: 'Min oppervlakte',
                        max_surface: 'Max oppervlakte',
                        rooms: 'Kamers',
                        energy_label: 'Energielabel'
                    };

                    Object.keys(this.filters).forEach(key => {
                        if (key !== 'sort' && key !== 'order' && this.filters[key] !== '') {
                            const value = this.filters[key];
                            const label = labelMap[key] || key;

                            if (key === 'type') {
                                labels[key] = value === 'sale' ? 'Koop' : 'Huur';
                            } else if (key === 'rooms') {
                                labels[key] = `${value}+ kamers`;
                            } else {
                                labels[key] = `${label}: ${value}`;
                            }
                        }
                    });

                    return labels;
                },

                async applyFilters() {
                    this.updateURL();
                    await this.fetchResults();
                },

                async fetchResults() {
                    this.loading = true;

                    const params = new URLSearchParams();
                    Object.keys(this.filters).forEach(key => {
                        if (this.filters[key] !== '') {
                            params.append(key, this.filters[key]);
                        }
                    });

                    try {
                        const response = await fetch(`{{ route('search') }}?${params.toString()}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();
                        this.resultsHtml = data.html;
                        this.paginationHtml = data.pagination;
                        this.total = data.total;
                    } catch (error) {
                        console.error('Error fetching results:', error);
                    } finally {
                        this.loading = false;
                    }
                },

                updateURL() {
                    const params = new URLSearchParams();
                    Object.keys(this.filters).forEach(key => {
                        if (this.filters[key] !== '') {
                            params.append(key, this.filters[key]);
                        }
                    });

                    const url = `{{ route('search') }}?${params.toString()}`;
                    window.history.pushState({}, '', url);
                },

                loadFromURL() {
                    const params = new URLSearchParams(window.location.search);
                    Object.keys(this.filters).forEach(key => {
                        this.filters[key] = params.get(key) || '';
                    });
                },

                clearFilter(key) {
                    this.filters[key] = '';
                    this.applyFilters();
                },

                clearAllFilters() {
                    Object.keys(this.filters).forEach(key => {
                        if (key !== 'sort' && key !== 'order') {
                            this.filters[key] = '';
                        }
                    });
                    this.applyFilters();
                }
            }
        }
    </script>
@endpush
