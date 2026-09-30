<script setup>

const emit = defineEmits(['close', 'search', 'reset']);

const props = defineProps({
    filters: {
        type: Object,
        required: true,
    },
});

function resetFilters() {
    emit('reset');
}

function performSearch() {
    emit('search', { ...props.filters });
}


</script>

<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
        <div class="w-full max-w-5xl max-h-[90vh] overflow-y-auto bg-white dark:bg-gray-800 rounded-lg shadow-xl">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Ricerca Avanzata
                    </h2>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Ricerca utenti utilizzando uno o più criteri.
                    </p>
                </div>

                <button type="button" @click="$emit('close')"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div class="p-6 space-y-8">
                <!-- Dati utente -->
                <section>
                    <h3 class="mb-4 text-sm font-semibold text-gray-900 dark:text-white">
                        Dati utente
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- ID User -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                ID User
                            </label>

                            <input v-model="props.filters.IDUser" type="text" placeholder="ID User" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500
                                    dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400" />
                        </div>

                        <!-- Username -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Username
                            </label>

                            <input v-model="props.filters.Username" type="text" placeholder="Username" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500
                                    dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400" />
                        </div>

                        <!-- Cod User Last Edit -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Cod User Last Edit
                            </label>

                            <input v-model="props.filters.CodUserLastEdit" type="text" placeholder="Cod User Last Edit"
                                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500
                                    dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400" />
                        </div>

                        <!-- Cod Agent -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Cod Agent
                            </label>

                            <input v-model="props.filters.CodAgent" type="number" placeholder="Cod Agent" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500
                                    dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400" />
                        </div>
                    </div>
                </section>

                <!-- Stato -->
                <section>
                    <h3 class="mb-4 text-sm font-semibold text-gray-900 dark:text-white">
                        Stato
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Power User -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Power User
                            </label>

                            <select v-model="props.filters.PowerUser" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500
                                    dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Tutti</option>
                                <option value="1">Sì</option>
                                <option value="0">No</option>
                            </select>
                        </div>

                        <!-- Disabilitato -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Disabilitato
                            </label>

                            <select v-model="props.filters.Disabled" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500
                                    dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Tutti</option>
                                <option value="1">Sì</option>
                                <option value="0">No</option>
                            </select>
                        </div>

                        <!-- Login Visible -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Login Visible
                            </label>

                            <select v-model="props.filters.LoginVisible" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500
                                    dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Tutti</option>
                                <option value="1">Sì</option>
                                <option value="0">No</option>
                            </select>
                        </div>

                        <!-- Change Pwd First Login -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Change Pwd First Login
                            </label>

                            <select v-model="props.filters.ChangePwdFirstLogin" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500
                                    dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Tutti</option>
                                <option value="1">Sì</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                </section>

                <!-- Database / Connessione -->
                <section>
                    <h3 class="mb-4 text-sm font-semibold text-gray-900 dark:text-white">
                        Database / Connessione
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- DB SPID -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                DB SPID
                            </label>

                            <input v-model="props.filters.DBSPID" type="number" placeholder="DB SPID" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500
                                    dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400" />
                        </div>

                        <!-- IP Address -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                IP Address
                            </label>

                            <input v-model="props.filters.IPADDRESS" type="text" placeholder="IP Address" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500
                                    dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400" />
                        </div>
                    </div>
                </section>

                <!-- Accessi -->
                <section>
                    <h3 class="mb-4 text-sm font-semibold text-gray-900 dark:text-white">
                        Accessi
                    </h3>

                    <div class="space-y-4">
                        <!-- DB Login Time -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                DB Login Time
                            </label>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block mb-1 text-xs text-gray-500 dark:text-gray-400">
                                        Da
                                    </label>

                                    <input v-model="props.filters.DBLOGINTIME_FROM" type="datetime-local" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500
                                            dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                                </div>

                                <div>
                                    <label class="block mb-1 text-xs text-gray-500 dark:text-gray-400">
                                        A
                                    </label>

                                    <input v-model="props.filters.DBLOGINTIME_TO" type="datetime-local" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500
                                            dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                                </div>
                            </div>
                        </div>

                        <!-- Last Login -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Last Login
                            </label>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block mb-1 text-xs text-gray-500 dark:text-gray-400">
                                        Da
                                    </label>

                                    <input v-model="props.filters.TimestampLastLogin_FROM" type="datetime-local" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500
                                            dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                                </div>

                                <div>
                                    <label class="block mb-1 text-xs text-gray-500 dark:text-gray-400">
                                        A
                                    </label>

                                    <input v-model="props.filters.TimestampLastLogin_TO" type="datetime-local" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500
                                            dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Date -->
                <section>
                    <h3 class="mb-4 text-sm font-semibold text-gray-900 dark:text-white">
                        Date
                    </h3>

                    <div class="space-y-4">
                        <!-- Timestamp INS -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Timestamp INS
                            </label>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block mb-1 text-xs text-gray-500 dark:text-gray-400">
                                        Da
                                    </label>

                                    <input v-model="props.filters.TimestampINS_FROM" type="datetime-local" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500
                                            dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                                </div>

                                <div>
                                    <label class="block mb-1 text-xs text-gray-500 dark:text-gray-400">
                                        A
                                    </label>

                                    <input v-model="props.filters.TimestampINS_TO" type="datetime-local" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500
                                            dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                                </div>
                            </div>
                        </div>

                        <!-- Timestamp EDT -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Timestamp EDT
                            </label>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block mb-1 text-xs text-gray-500 dark:text-gray-400">
                                        Da
                                    </label>

                                    <input v-model="props.filters.TimestampEDT_FROM" type="datetime-local" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500
                                            dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                                </div>

                                <div>
                                    <label class="block mb-1 text-xs text-gray-500 dark:text-gray-400">
                                        A
                                    </label>

                                    <input v-model="props.filters.TimestampEDT_TO" type="datetime-local" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500
                                            dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-200 dark:border-gray-700">

                <button type="button" @click="resetFilters"
                    class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100
        focus:ring-4 focus:ring-gray-200 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600 dark:focus:ring-gray-700">
                    Reset filtri
                </button>
                <button type="button" @click="$emit('close')"
                    class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100
                        focus:ring-4 focus:ring-gray-200 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600 dark:focus:ring-gray-700">
                    Annulla
                </button>

                <button type="button" @click="performSearch" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700
                        focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800">
                    Cerca
                </button>
            </div>
        </div>
    </div>
</template>