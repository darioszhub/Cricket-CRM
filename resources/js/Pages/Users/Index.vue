<template>

    <Head title="Users" />
    <div>
        <h1>Lista Utenti</h1>
        <ul>
            <li v-for="user in users" :key="user.IDUser">
                {{ user.Username }}
            </li>
        </ul>
    </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';

defineProps({
    users: Array,
});
document.title = "Pagina Vue OK";
</script>