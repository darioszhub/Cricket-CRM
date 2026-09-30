<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AdvancedSearchModal from '@/Components/Users/AdvancedSearchModal.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, reactive } from 'vue';

const props = defineProps({
    users: Object,
    perPage: [Number, String],
    search: String,
});

const searchInput = ref(props.search ?? '');
const showAdvancedSearch = ref(false);

const advancedFilters = reactive({
    IDUser: '',
    Username: '',
    CodUserLastEdit: '',
    CodAgent: '',

    PowerUser: '',
    Disabled: '',
    LoginVisible: '',
    ChangePwdFirstLogin: '',

    DBSPID: '',
    IPADDRESS: '',

    DBLOGINTIME_FROM: '',
    DBLOGINTIME_TO: '',

    TimestampLastLogin_FROM: '',
    TimestampLastLogin_TO: '',

    TimestampINS_FROM: '',
    TimestampINS_TO: '',

    TimestampEDT_FROM: '',
    TimestampEDT_TO: '',
});

let searchTimeout = null;

function changePerPage(value) {
    router.get(
        route('users.index'),
        { perPage: value },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
}

function searchUsers() {
    clearTimeout(searchTimeout);

    const search = searchInput.value.trim();

    if (search.length === 0) {
        router.get(
            route('users.index'),
            {
                perPage: props.perPage,
            },
            {
                preserveState: true,
                preserveScroll: true,
            }
        );

        return;
    }

    if (search.length < 4) {
        return;
    }

    searchTimeout = setTimeout(() => {
        router.get(
            route('users.index'),
            {
                search: search,
                perPage: props.perPage,
            },
            {
                preserveState: true,
                preserveScroll: true,
            }
        );
    }, 400);
}

function advancedSearch(filters) {
    Object.assign(advancedFilters, filters);

    router.get(
        route('users.index'),
        {
            ...advancedFilters,
            perPage: props.perPage,
        },
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                showAdvancedSearch.value = false;
            },
        }
    );
}

function resetAdvancedSearch() {
    Object.keys(advancedFilters).forEach((key) => {
        advancedFilters[key] = '';
    });

    router.get(
        route('users.index'),
        {
            perPage: props.perPage,
        },
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                showAdvancedSearch.value = false;
            },
        }
    );
}

function goToPage(url) {
    if (!url) {
        return;
    }

    router.get(url, {}, {
        preserveState: true,
        preserveScroll: true,
    });
}
</script>

<template>

    <Head title="Utenti" />

    <AuthenticatedLayout>


        <div class="py-12">
            <div class="max-w-full mx-auto sm:px-6 lg:px-8">

                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">

                    <div class="flex justify-between items-end mb-6">

                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                Lista Utenti
                            </h3>

                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Gestione degli utenti del sistema
                            </p>
                        </div>

                        <div class="flex items-center gap-6">

                            <!-- Ricerca -->
                            <div class="flex">
                                <input v-model="searchInput" @input="searchUsers" type="text"
                                    placeholder="Cerca utenti..." class="w-64 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500
                                    dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400" />

                                <button type="button" @click="showAdvancedSearch = true"
                                    class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-l-0 border-gray-300 rounded-r-lg hover:bg-gray-100
                                    focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600">
                                    Ricerca Avanzata</button>
                            </div>

                            <!-- Visualizza -->
                            <div class="flex items-center gap-2">
                                <label for="perPage" class="text-sm text-gray-700 dark:text-gray-300">
                                    Visualizza:
                                </label>

                                <select id="perPage" :value="perPage" @change="changePerPage($event.target.value)"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="200">200</option>
                                    <option value="all">Tutti</option>
                                </select>
                            </div>

                        </div>

                    </div>

                    <div class="relative overflow-auto" style="max-height: 600px;">

                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 sticky top-0 z-10">
                                <tr>
                                    <th class="px-4 py-3 whitespace-nowrap">ID User</th>
                                    <th class="px-4 py-3 whitespace-nowrap">Username</th>
                                    <th class="px-4 py-3 whitespace-nowrap">Cod Agent</th>
                                    <th class="px-4 py-3 whitespace-nowrap">Power User</th>
                                    <th class="px-4 py-3 whitespace-nowrap">Disabilitato</th>
                                    <th class="px-4 py-3 whitespace-nowrap">DB SPID</th>
                                    <th class="px-4 py-3 whitespace-nowrap">DB Login Time</th>
                                    <th class="px-4 py-3 whitespace-nowrap">IP Address</th>
                                    <th class="px-4 py-3 whitespace-nowrap">Last Login</th>
                                    <th class="px-4 py-3 whitespace-nowrap">Login Visible</th>
                                    <th class="px-4 py-3 whitespace-nowrap">Change Pwd First Login</th>
                                    <th class="px-4 py-3 whitespace-nowrap">Timestamp INS</th>
                                    <th class="px-4 py-3 whitespace-nowrap">Timestamp EDT</th>
                                    <th class="px-4 py-3 whitespace-nowrap">Cod User Last Edit</th>
                                    <th class="px-4 py-3 whitespace-nowrap text-center">Azioni</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="user in users.data" :key="user.IDUser"
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">{{
                                        user.IDUser }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">{{ user.Username }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">{{ user.CodAgent }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">{{ user.PowerUser ? 'Sì' : 'No' }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">{{ user.Disabled ? 'Sì' : 'No' }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">{{ user.DBSPID }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">{{ user.DBLOGINTIME }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">{{ user.IPADDRESS }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">{{ user.TimestampLastLogin }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">{{ user.LoginVisible ? 'Sì' : 'No' }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">{{ user.ChangePwdFirstLogin ? 'Sì' : 'No' }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">{{ user.TimestampINS }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">{{ user.TimestampEDT }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">{{ user.CodUserLastEdit }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2">

                                            <!-- Modifica -->
                                            <button type="button" title="Modifica utente" class="p-2 text-blue-600 rounded-lg hover:bg-blue-100
                dark:text-blue-400 dark:hover:bg-gray-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5m-1.414-9.414a2 2 0 0 1 2.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>

                                            <!-- Cancellazione -->
                                            <button type="button" title="Elimina utente" class="p-2 text-red-600 rounded-lg hover:bg-red-100
                dark:text-red-400 dark:hover:bg-gray-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3m-4 0h14" />
                                                </svg>
                                            </button>

                                        </div>
                                    </td>
                                </tr>

                                <tr v-if="users.data.length === 0">
                                    <td colspan="15" class="px-6 py-4 text-center text-gray-600 dark:text-gray-400">
                                        Nessun utente trovato.</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>

                    <div v-if="perPage !== 'all'" class="flex justify-between items-center mt-4">

                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            Visualizzati {{ users.from }} - {{ users.to }}
                            di {{ users.total }} utenti
                        </span>

                        <div class="flex gap-1">

                            <button v-for="link in users.links" :key="link.label" @click="goToPage(link.url)"
                                :disabled="!link.url" v-html="link.label" class="px-3 py-2 text-sm border rounded-md
                                       disabled:opacity-50 dark:text-white
                                       hover:bg-gray-100 dark:hover:bg-gray-700
                                       dark:border-gray-600" :class="{ 'bg-blue-600 text-white': link.active }" />
                        </div>

                    </div>

                </div>

            </div>
        </div>

        <!-- Modal Ricerca Avanzata -->
        <AdvancedSearchModal v-if="showAdvancedSearch" :filters="advancedFilters" @close="showAdvancedSearch = false"
            @search="advancedSearch" @reset="resetAdvancedSearch" />

    </AuthenticatedLayout>
</template>