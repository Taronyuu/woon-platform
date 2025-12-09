@extends('layouts.app')

@section('title', 'Bereken je maandlasten - Oxxen.nl')

@section('content')


    <main class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">

            <div class="mb-6">
                <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-700 flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Terug naar home
                </a>
            </div>

            <section class="relative bg-gradient-to-br from-blue-600 via-purple-600 to-blue-800 text-white rounded-3xl p-12 mb-8 overflow-hidden">
                <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZGVmcz48cGF0dGVybiBpZD0iZ3JpZCIgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBwYXR0ZXJuVW5pdHM9InVzZXJTcGFjZU9uVXNlIj48cGF0aCBkPSJNIDQwIDAgTCAwIDAgMCA0MCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSJyZ2JhKDI1NSwyNTUsMjU1LDAuMSkiIHN0cm9rZS13aWR0aD0iMSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNncmlkKSIvPjwvc3ZnPg==')] opacity-30"></div>
                <div class="max-w-2xl mx-auto text-center relative z-10">
                    <div class="inline-block mb-4">
                        <span class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-semibold">💰 Hypotheek Calculator</span>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-extrabold mb-4 leading-tight">Bereken je maandlasten</h1>
                    <p class="text-xl text-blue-100">Bereken eenvoudig je hypotheeklasten per maand met actuele rentetarieven</p>
                </div>
            </section>

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                <div class="p-8" x-data="mortgageCalculator()">
                    <div class="space-y-6">
                        <div>
                            <label for="amount" class="block text-sm font-semibold text-gray-700 mb-2">
                                Hypotheekbedrag
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 font-medium">
                                    €
                                </span>
                                <input
                                    type="number"
                                    id="amount"
                                    x-model.number="amount"
                                    @input="calculate()"
                                    class="w-full pl-10 pr-4 py-4 border-2 border-gray-200 rounded-xl text-gray-900 text-lg font-semibold focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                                    placeholder="250000"
                                >
                            </div>
                        </div>

                        <div>
                            <label for="duration" class="block text-sm font-semibold text-gray-700 mb-2">
                                Looptijd
                            </label>
                            <select
                                id="duration"
                                x-model.number="duration"
                                @change="calculate()"
                                class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl text-gray-900 text-lg font-semibold focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                            >
                                <option value="10">10 jaar</option>
                                <option value="15">15 jaar</option>
                                <option value="20">20 jaar</option>
                                <option value="25">25 jaar</option>
                                <option value="30" selected>30 jaar</option>
                            </select>
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">
                                Hypotheekvorm
                            </label>
                            <select
                                id="type"
                                x-model="mortgageType"
                                @change="fetchInterestRate()"
                                class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl text-gray-900 text-lg font-semibold focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                            >
                                <option value="ANNUITAIR">Annuïteit</option>
                                <option value="LINEAR">Lineair</option>
                            </select>
                        </div>

                        <div x-show="loading" class="flex items-center justify-center py-8">
                            <div class="flex flex-col items-center">
                                <svg class="animate-spin h-12 w-12 text-blue-600 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <p class="text-gray-600 font-medium">Actuele rentetarieven ophalen...</p>
                            </div>
                        </div>

                        <div x-show="!loading && monthlyPayment > 0" class="relative overflow-hidden rounded-2xl">
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-500 via-purple-500 to-blue-600 opacity-10"></div>
                            <div class="relative bg-gradient-to-br from-blue-50 to-purple-50 border-2 border-blue-200 p-8 rounded-2xl">
                                <div class="flex items-baseline justify-between mb-6">
                                    <div>
                                        <p class="text-sm text-gray-600 font-semibold uppercase tracking-wide mb-2">Je maandlasten</p>
                                        <p class="text-5xl font-extrabold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent" x-text="'€ ' + monthlyPayment.toFixed(2)"></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-600 font-semibold uppercase tracking-wide mb-2">Rentepercentage</p>
                                        <p class="text-3xl font-bold text-gray-900" x-text="interestRate.toFixed(2) + '%'"></p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-3 gap-4 pt-6 border-t-2 border-blue-200">
                                    <div class="text-center">
                                        <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide mb-1">Hypotheek</p>
                                        <p class="text-lg font-bold text-gray-900" x-text="'€ ' + amount.toLocaleString()"></p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide mb-1">Looptijd</p>
                                        <p class="text-lg font-bold text-gray-900" x-text="duration + ' jaar'"></p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide mb-1">Totale rente</p>
                                        <p class="text-lg font-bold text-gray-900" x-text="'€ ' + totalInterest.toLocaleString('nl-NL', {maximumFractionDigits: 0})"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div x-show="error" class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700 font-medium" x-text="error"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-8 border-t-2 border-gray-200">
                        <div class="bg-blue-50 rounded-xl p-6">
                            <h3 class="font-bold text-gray-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Let op
                            </h3>
                            <p class="text-sm text-gray-700 leading-relaxed">
                                Dit is een indicatieve berekening. De daadwerkelijke maandlasten kunnen afwijken afhankelijk van je persoonlijke situatie, actuele rentetarieven en eventuele extra kosten zoals NHG-premie. Neem contact op met een adviseur voor een compleet overzicht.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>


@push('scripts')
    <script>
        function mortgageCalculator() {
            const urlParams = new URLSearchParams(window.location.search);

            return {
                amount: parseInt(urlParams.get('amount')) || 250000,
                duration: parseInt(urlParams.get('duration')) || 30,
                mortgageType: 'ANNUITAIR',
                interestRate: 0,
                monthlyPayment: 0,
                totalInterest: 0,
                loading: true,
                error: null,

                init() {
                    this.fetchInterestRate();
                },

                async fetchInterestRate() {
                    this.loading = true;
                    this.error = null;

                    try {
                        const params = new URLSearchParams({
                            product: 'BUDGET',
                            type: this.mortgageType
                        });

                        const response = await fetch('/api/interest-rate?' + params, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (data.error) {
                            this.error = data.error;
                        } else if (data.interestRate) {
                            this.interestRate = data.interestRate;
                            this.calculate();
                        } else {
                            this.error = 'Onverwacht antwoord van server';
                        }
                    } catch (err) {
                        this.error = 'Fout bij ophalen rentetarief: ' + err.message;
                    } finally {
                        this.loading = false;
                    }
                },

                calculate() {
                    if (!this.amount || !this.duration || !this.interestRate) {
                        return;
                    }

                    const monthlyRate = this.interestRate / 100 / 12;
                    const numberOfPayments = this.duration * 12;

                    if (this.mortgageType === 'ANNUITAIR') {
                        if (monthlyRate === 0) {
                            this.monthlyPayment = this.amount / numberOfPayments;
                        } else {
                            this.monthlyPayment = this.amount *
                                (monthlyRate * Math.pow(1 + monthlyRate, numberOfPayments)) /
                                (Math.pow(1 + monthlyRate, numberOfPayments) - 1);
                        }
                    } else {
                        const principal = this.amount / numberOfPayments;
                        const interest = this.amount * monthlyRate;
                        this.monthlyPayment = principal + interest;
                    }

                    this.totalInterest = (this.monthlyPayment * numberOfPayments) - this.amount;
                }
            }
        }
    </script>

@endpush
@endsection
