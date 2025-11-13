import './bootstrap';
import Alpine from 'alpinejs';

Alpine.data('cityAutocomplete', () => ({
    search: '',
    cities: [],
    filteredCities: [],
    isOpen: false,
    selectedIndex: -1,

    async init() {
        try {
            const response = await fetch('/api/cities');
            const json = await response.json();
            this.cities = json.data || json;
        } catch (error) {
            console.error('Failed to load cities:', error);
        }
    },

    filter() {
        if (this.search.length < 2) {
            this.filteredCities = [];
            this.isOpen = false;
            this.selectedIndex = -1;
            return;
        }

        const searchLower = this.search.toLowerCase();
        this.filteredCities = this.cities
            .filter(city => city.name.toLowerCase().includes(searchLower))
            .slice(0, 10);
        this.isOpen = this.filteredCities.length > 0;
        this.selectedIndex = -1;
    },

    selectCity(city) {
        this.search = city.name;
        this.isOpen = false;
        this.selectedIndex = -1;
    },

    handleKeydown(event) {
        if (!this.isOpen) return;

        if (event.key === 'ArrowDown') {
            event.preventDefault();
            this.selectedIndex = Math.min(this.selectedIndex + 1, this.filteredCities.length - 1);
        } else if (event.key === 'ArrowUp') {
            event.preventDefault();
            this.selectedIndex = Math.max(this.selectedIndex - 1, -1);
        } else if (event.key === 'Enter' && this.selectedIndex >= 0) {
            event.preventDefault();
            this.selectCity(this.filteredCities[this.selectedIndex]);
        } else if (event.key === 'Escape') {
            this.isOpen = false;
            this.selectedIndex = -1;
        }
    }
}));

window.Alpine = Alpine;
Alpine.start();
