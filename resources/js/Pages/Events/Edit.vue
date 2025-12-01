<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    event: Object,
});

const form = useForm({
    year: props.event.year,
    name: props.event.name || "",
    description: props.event.description || "",
    registration_start: props.event.registration_start || "",
    registration_end: props.event.registration_end || "",
    exchange_date: props.event.exchange_date || "",
    is_active: props.event.is_active,
});

const submit = () => {
    form.patch(route("events.update", props.event.id));
};
</script>

<template>
    <Head title="Ažuriraj Događaj" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Ažuriraj {{ event.name || `Secret Santa ${event.year}` }}
                </h2>
                <Link
                    :href="route('events.show', event.id)"
                    class="text-gray-600 hover:text-gray-900"
                >
                    ← Nazad
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Year -->
                            <div>
                                <label
                                    for="year"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Godina *
                                </label>
                                <input
                                    id="year"
                                    v-model="form.year"
                                    type="number"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required
                                />
                                <div
                                    v-if="form.errors.year"
                                    class="text-red-600 text-sm mt-1"
                                >
                                    {{ form.errors.year }}
                                </div>
                            </div>

                            <!-- Name -->
                            <div>
                                <label
                                    for="name"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Naziv
                                </label>
                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Secret Santa 2024"
                                />
                                <div
                                    v-if="form.errors.name"
                                    class="text-red-600 text-sm mt-1"
                                >
                                    {{ form.errors.name }}
                                </div>
                            </div>

                            <!-- Description -->
                            <div>
                                <label
                                    for="description"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Opis
                                </label>
                                <textarea
                                    id="description"
                                    v-model="form.description"
                                    rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Kratak opis događaja..."
                                ></textarea>
                                <div
                                    v-if="form.errors.description"
                                    class="text-red-600 text-sm mt-1"
                                >
                                    {{ form.errors.description }}
                                </div>
                            </div>

                            <!-- Registration Dates -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label
                                        for="registration_start"
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Prijave od
                                    </label>
                                    <input
                                        id="registration_start"
                                        v-model="form.registration_start"
                                        type="date"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    />
                                    <div
                                        v-if="form.errors.registration_start"
                                        class="text-red-600 text-sm mt-1"
                                    >
                                        {{ form.errors.registration_start }}
                                    </div>
                                </div>

                                <div>
                                    <label
                                        for="registration_end"
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Prijave do
                                    </label>
                                    <input
                                        id="registration_end"
                                        v-model="form.registration_end"
                                        type="date"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    />
                                    <div
                                        v-if="form.errors.registration_end"
                                        class="text-red-600 text-sm mt-1"
                                    >
                                        {{ form.errors.registration_end }}
                                    </div>
                                </div>
                            </div>

                            <!-- Exchange Date -->
                            <div>
                                <label
                                    for="exchange_date"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Datum razmene poklona
                                </label>
                                <input
                                    id="exchange_date"
                                    v-model="form.exchange_date"
                                    type="datetime-local"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                />
                                <div
                                    v-if="form.errors.exchange_date"
                                    class="text-red-600 text-sm mt-1"
                                >
                                    {{ form.errors.exchange_date }}
                                </div>
                            </div>

                            <!-- Is Active -->
                            <div class="flex items-center">
                                <input
                                    id="is_active"
                                    v-model="form.is_active"
                                    type="checkbox"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                />
                                <label
                                    for="is_active"
                                    class="ml-2 block text-sm text-gray-900"
                                >
                                    Aktiviraj ovaj događaj
                                </label>
                            </div>
                            <p class="text-sm text-gray-600">
                                Napomena: Aktiviranjem ovog događaja, svi drugi
                                događaji će biti deaktivirani.
                            </p>

                            <!-- Submit Button -->
                            <div class="flex items-center justify-end">
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                                >
                                    Ažuriraj Događaj
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
