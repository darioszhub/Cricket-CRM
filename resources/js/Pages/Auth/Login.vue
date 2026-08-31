<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

/* const form = useForm({
    email: '',
    password: '',
    remember: false,
}); */

//Form personalizzato
const form = useForm({
    Username: '',
    Keyword: '',
    remember: false,
})

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};




</script>


<template>


    <GuestLayout>


        <Head title="Log in" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="Username" value="Username" />

                <TextInput id="Username" type="text" class="mt-1 block w-full" v-model="form.Username" required
                    autofocus autocomplete="username" />

                <InputError class="mt-2" :message="form.errors.Username" />
            </div>

            <div class="mt-4">
                <InputLabel for="Keyword" value="Password" />

                <TextInput id="Keyword" type="password" class="mt-1 block w-full" v-model="form.Keyword" required
                    autocomplete="current-password" />

                <InputError class="mt-2" :message="form.errors.Keyword" />
            </div>



            <div class="flex items-center justify-end mt-4">
                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>