<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

const props = defineProps({
    organization: Object,
});

const form = useForm({
    name: props.organization.name,
    description: props.organization.description,
    is_active: props.organization.is_active,
});

const submit = () => {
    form.patch(route("organizations.update", props.organization.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Izmeni Organizaciju" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                Izmeni Organizaciju
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
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.name"
                                />
                            </div>

                            <div class="mb-4">
                                <InputLabel
                                    for="description"
                                    value="Opis (opciono)"
                                />
                                <textarea
                                    id="description"
                                    v-model="form.description"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    rows="4"
                                ></textarea>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.description"
                                />
                            </div>

                            <div class="mb-6">
                                <label class="flex items-center">
                                    <input
                                        type="checkbox"
                                        v-model="form.is_active"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                    />
                                    <span class="ml-2 text-sm text-gray-600"
                                        >Organizacija je aktivna</span
                                    >
                                </label>
                                <p class="text-xs text-gray-500 mt-1">
                                    Neaktivne organizacije ne mogu kreirati nove
                                    evente i ne mogu primiti nove članove.
                                </p>
                            </div>

                            <div class="flex items-center gap-4">
                                <PrimaryButton :disabled="form.processing">
                                    Sačuvaj Izmene
                                </PrimaryButton>
                                <a
                                    :href="
                                        route(
                                            'organizations.show',
                                            organization.id
                                        )
                                    "
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
