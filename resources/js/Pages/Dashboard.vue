<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Line } from 'vue-chartjs';
import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend, Filler } from 'chart.js';

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend, Filler);

// Dati del grafico statico della settimana
const chartData = ref({
    labels: ['Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab', 'Dom'],
    datasets: [{
        label: 'Ordini',
        data: [31, 40, 28, 51, 42, 109, 100],
        borderColor: '#3B82F6', // Colore della linea
        backgroundColor: 'rgba(59, 130, 246, 0.2)', // Colore dell'area
        tension: 0.4,
        fill: true,
    }]
});

// Opzioni del grafico, inclusa la gestione del colore per la modalità scura
const chartOptions = ref({
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            labels: {
                color: '#FFFFFF' // Colore del testo della legenda
            }
        },
        tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.7)',
            titleColor: '#FFFFFF',
            bodyColor: '#FFFFFF',
        }
    },
    scales: {
        x: {
            grid: {
                color: 'rgba(255, 255, 255, 0.1)' // Colore delle linee della griglia X
            },
            ticks: {
                color: '#FFFFFF' // Colore del testo dell'asse X
            }
        },
        y: {
            grid: {
                color: 'rgba(255, 255, 255, 0.1)' // Colore delle linee della griglia Y
            },
            ticks: {
                color: '#FFFFFF' // Colore del testo dell'asse Y
            }
        }
    }
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>

        <!-- <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">Dashboard</h2>
        </template> -->

        <div class="pt-4 pb-12">
            <div class="mx-auto sm:px-4 lg:px-4">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-white">
                        <div class="w-full">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                <!-- Widget 4: Grafico Settimanale -->
                                <div
                                    class="col-span-1 sm:col-span-2 lg:col-span-3 bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                                    <h2 class="text-2xl font-bold mb-4">Ordini Settimanali</h2>
                                    <div class="w-full" style="height: 350px;">
                                        <Line :data="chartData" :options="chartOptions" />
                                    </div>
                                </div>
                                <!-- Widget 1: Ordini Giornalieri -->
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 transform hover:scale-105 transition-transform duration-300">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xl font-semibold">Ordini Giornalieri</span>
                                        <svg class="w-8 h-8 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="mt-4 text-4xl font-bold text-gray-800 dark:text-white">125</div>
                                    <p class="text-sm text-gray-500 mt-2">Aggiornato oggi</p>
                                </div>
                                <!-- Widget 2: Numero di Liste Attive -->
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 transform hover:scale-105 transition-transform duration-300">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xl font-semibold">Numero di Liste Attive</span>
                                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5m-1-4v1m-1-2v3m-2-3v3m-2-3v3m-2-3v3M10 20h2m-1-4v1m-1-2v3m-2-3v3M5 20h2m-1-4v1m-1-2v3m-2-3v3M4 12V4h16v8h-4a2 2 0 01-2-2v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2a2 2 0 01-2 2H4z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="mt-4 text-4xl font-bold text-gray-800 dark:text-white">45</div>
                                    <p class="text-sm text-gray-500 mt-2">Attualmente</p>
                                </div>
                                <!-- Widget 3: Numero di Chiamate Odierne -->
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 transform hover:scale-105 transition-transform duration-300">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xl font-semibold">Chiamate Odierne</span>
                                        <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="mt-4 text-4xl font-bold text-gray-800 dark:text-white">32</div>
                                    <p class="text-sm text-gray-500 mt-2">Dal 01/01/2025</p>
                                </div>
                                
                                <!-- Nuovi Widget: Lista degli Ordini -->
                                <div
                                    class="col-span-1 sm:col-span-2 lg:col-span-3 bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                                    <h2 class="text-2xl font-bold mb-4">Lista degli Ordini Recenti</h2>
                                    <ul class="space-y-4">
                                        <li
                                            class="flex justify-between items-center p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                            <div class="flex items-center space-x-4">
                                                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2m-9 0V3m6 2V3m-9 3h12a2 2 0 012 2v2a2 2 0 01-2 2h-12a2 2 0 01-2-2V8a2 2 0 012-2z">
                                                    </path>
                                                </svg>
                                                <div>
                                                    <div class="font-semibold text-gray-900 dark:text-white">Ordine #12345</div>
                                                    <div class="text-sm text-gray-500">20/08/2025</div>
                                                </div>
                                            </div>
                                            <span class="font-bold text-green-500">€ 150.00</span>
                                        </li>
                                        <li
                                            class="flex justify-between items-center p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                            <div class="flex items-center space-x-4">
                                                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2m-9 0V3m6 2V3m-9 3h12a2 2 0 012 2v2a2 2 0 01-2 2h-12a2 2 0 01-2-2V8a2 2 0 012-2z">
                                                    </path>
                                                </svg>
                                                <div>
                                                    <div class="font-semibold text-gray-900 dark:text-white">Ordine #12344</div>
                                                    <div class="text-sm text-gray-500">19/08/2025</div>
                                                </div>
                                            </div>
                                            <span class="font-bold text-green-500">€ 75.50</span>
                                        </li>
                                        <li
                                            class="flex justify-between items-center p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                            <div class="flex items-center space-x-4">
                                                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2m-9 0V3m6 2V3m-9 3h12a2 2 0 012 2v2a2 2 0 01-2 2h-12a2 2 0 01-2-2V8a2 2 0 012-2z">
                                                    </path>
                                                </svg>
                                                <div>
                                                    <div class="font-semibold text-gray-900 dark:text-white">Ordine #12343</div>
                                                    <div class="text-sm text-gray-500">18/08/2025</div>
                                                </div>
                                            </div>
                                            <span class="font-bold text-green-500">€ 220.00</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
