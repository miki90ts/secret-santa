<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    assignment: Object,
    event: Object,
});

const giftForm = useForm({
    description: props.assignment.gift?.description || "",
    is_satisfied: props.assignment.gift?.is_satisfied ?? true,
    feedback: props.assignment.gift?.feedback || "",
});

const submitGift = () => {
    giftForm.post(route("gifts.store", props.assignment.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Moj Poklon" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Moj Secret Santa Poklon
                </h2>
                <Link
                    :href="route('events.show', event.id)"
                    class="text-gray-600 hover:text-gray-900"
                >
                    ← Nazad na događaj
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Info Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="mb-4 text-xl font-bold">
                            🎁 Ovde možeš uneti šta si dobio/la kao poklon i
                            ostaviti feedback.
                        </h4>
                    </div>
                </div>

                <!-- Gift Report Form -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-4">Šta si dobio/la?</h3>

                        <form @submit.prevent="submitGift" class="space-y-4">
                            <!-- Description -->
                            <div>
                                <label
                                    for="description"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Opis poklona *
                                </label>
                                <textarea
                                    id="description"
                                    v-model="giftForm.description"
                                    rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Npr. Knjiga, šolja, čokolada..."
                                    required
                                ></textarea>
                                <div
                                    v-if="giftForm.errors.description"
                                    class="text-red-600 text-sm mt-1"
                                >
                                    {{ giftForm.errors.description }}
                                </div>
                            </div>

                            <!-- Satisfaction -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Da li si zadovoljan/na? *
                                </label>
                                <div class="flex gap-4">
                                    <label class="flex items-center">
                                        <input
                                            v-model="giftForm.is_satisfied"
                                            type="radio"
                                            :value="true"
                                            class="mr-2"
                                        />
                                        😊 Da, jako mi se sviđa!
                                    </label>
                                    <label class="flex items-center">
                                        <input
                                            v-model="giftForm.is_satisfied"
                                            type="radio"
                                            :value="false"
                                            class="mr-2"
                                        />
                                        😐 Nije baš ono što sam očekivao/la
                                    </label>
                                </div>
                                <div
                                    v-if="giftForm.errors.is_satisfied"
                                    class="text-red-600 text-sm mt-1"
                                >
                                    {{ giftForm.errors.is_satisfied }}
                                </div>
                            </div>

                            <!-- Feedback -->
                            <div>
                                <label
                                    for="feedback"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Komentar (opcionalno)
                                </label>
                                <textarea
                                    id="feedback"
                                    v-model="giftForm.feedback"
                                    rows="2"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Dodaj svoj komentar o poklonu..."
                                ></textarea>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex items-center justify-end">
                                <button
                                    type="submit"
                                    :disabled="giftForm.processing"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                                >
                                    {{
                                        assignment.gift ? "Ažuriraj" : "Sačuvaj"
                                    }}
                                    Poklon
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
