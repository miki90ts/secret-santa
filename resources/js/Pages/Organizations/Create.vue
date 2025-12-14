<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

const form = useForm({
    name: "",
    description: "",
});

const submit = () => {
    form.post(route("organizations.store"), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Kreiraj Organizaciju" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                Kreiraj Novu Organizaciju
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <InputLabel
                                    for="name"
                                    value="Naziv Organizacije *"
                                />
                                <TextInput
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                    placeholder="Npr. Moja Firma d.o.o."
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.name"
                                />
                            </div>

                            <div class="mb-6">
                                <InputLabel
                                    for="description"
                                    value="Opis (opciono)"
                                />
                                <textarea
                                    id="description"
                                    v-model="form.description"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    rows="4"
                                    placeholder="Kratki opis organizacije..."
                                ></textarea>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.description"
                                />
                            </div>

                            <div
                                class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6"
                            >
                                <p class="text-sm text-gray-700">
                                    ℹ️ Nakon kreiranja organizacije, dobićete
                                    jedinstveni link koji možete podeliti sa
                                    kolegama da se pridruže.
                                </p>
                            </div>

                            <div class="flex items-center gap-4">
                                <PrimaryButton :disabled="form.processing">
                                    Kreiraj Organizaciju
                                </PrimaryButton>
                                <a
                                    :href="route('organizations.index')"
                                    class="text-gray-600 hover:text-gray-900"
                                >
                                    Otkaži
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
