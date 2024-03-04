<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3';
import GuestLayout from "@/Layouts/GuestLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import Checkbox from "@/Components/Checkbox.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { ref } from "vue";

const props = defineProps({
    status: String | Boolean | Object | Array,
});

const page = usePage()

// Reactive state
const debouncedValue = ref('');

const form = useForm({
    cnic: '',
    phone: '',
    address: '',
    name: '',
});

const searchUser = async () => {
    if (form.cnic.length > 12 && form.cnic.length < 14) {
       await form.post(route('welcome.search.user'), {
           onSuccess: (result) => {
           }
       }, {
           preserveState: true,
       });
    }
};

// Debounce function to limit the rate of function calls
const debounce = (func, delay) => {
    let timeoutId;
    return (...args) => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
            func(...args);
        }, delay);
    };
};

// Debounced function to handle input changes
const searchCnic = debounce((value) => {
    debouncedValue.value = value;
    searchUser();
}, 600); // Adjust the delay according to your needs

const exportUsers = () => {
    form.put(route('welcome.download'), {
        onSuccess: (result) => {

        },
    });
}

const submit = () => {
    form.patch(route('welcome.store'), {
        onSuccess: (result) => {
            form.reset('cnic', 'phone', 'address', 'name');
            form.cnic = ''
        },
    });
};
</script>

<template>
    <div>
    <GuestLayout>
        <Head title="Rashan Programme"/>
        <div v-if="status?.status" class="mb-4 font-medium text-sm text-green-600">
            {{ status?.message }}
        </div>

        <div v-else class="mb-4 font-medium text-sm text-red-600">
            {{ status?.message }}
        </div>

        <div class="flex mb-2 w-100">
            <PrimaryButton class="ms-auto" @click="exportUsers">
                Export Data
            </PrimaryButton>
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="cnic" value="CNIC"/>

                <TextInput
                    @keyup="searchCnic"
                    id="cnic"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.cnic"
                    required
                    autofocus
                />

                <InputError class="mt-2" :message="form.errors.cnic"/>
            </div>

            <Transition
                enter-active-class="ease-out duration-300"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="ease-in duration-200"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div v-show="status?.status" class="transform transition-all">
                    <div class="mt-4">
                    <InputLabel for="name" value="Name"/>

                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.name"
                            required
                        />

                        <InputError class="mt-2" :message="form.errors.phone"/>
                    </div>
                    <div class="mt-4">
                        <InputLabel for="phone" value="Phone Number"/>

                        <TextInput
                            id="phone"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.phone"
                            required
                        />

                        <InputError class="mt-2" :message="form.errors.phone"/>
                    </div>

                    <div class="mt-4">
                        <InputLabel for="address" value="Address"/>

                        <TextInput
                            id="address"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.address"
                            required
                        />

                        <InputError class="mt-2" :message="form.errors.address"/>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing || !form.name || !form.address || !form.phone }" :disabled="form.processing && !form.name || !form.address || !form.phone">
                            Save
                        </PrimaryButton>
                    </div>
                </div>
            </Transition>
        </form>
    </GuestLayout>
    </div>
</template>

<style>

</style>
