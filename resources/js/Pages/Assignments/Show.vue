<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    assignment: Object,
    event: Object,
});

const giftForm = useForm({
    description: props.assignment.gift?.description || '',
    is_satisfied: props.assignment.gift?.is_satisfied ?? true,
    feedback: props.assignment.gift?.feedback || '',
});

const submitGift = () => {
    giftForm.post(route('gifts.store', props.assignment.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Moja Dodela" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Moja Secret Santa Dodela
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
                <!-- Receiver Info Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-2xl font-bold mb-4">
                            🎁 Kupuješ poklon za: {{ assignment.receiver.name }}
                        </h3>

                        <!-- Wishes -->
                        <div v-if="assignment.receiver.wishes && assignment.receiver.wishes.length > 0" class="space-y-4">
                            <div
                                v-for="wish in assignment.receiver.wishes"
                                :key="wish.id"
                                class="border rounded-lg p-4"
                            >
                                <div v-if="wish.want" class="mb-3">
                                    <h4 class="text-green-600 font-semibold mb-2">✓ Šta voli:</h4>
                                    <p class="text-gray-700 whitespace-pre-line">{{ wish.want }}</p>
                                </div>

                                <div v-if="wish.does_not_want" class="mb-3">
                                    <h4 class="text-red-600 font-semibold mb-2">✗ Šta ne voli:</h4>
                                    <p class="text-gray-700 whitespace-pre-line">{{ wish.does_not_want }}</p>
                                </div>

                                <!-- Comments / Suggestions -->
                                <div v-if="wish.comments && wish.comments.length > 0" class="mt-4">
                                    <h4 class="font-semibold mb-2 text-gray-700">💡 Sugestije kolega:</h4>
                                    <div class="space-y-2">
                                        <div
                                            v-for="comment in wish.comments"
                                            :key="comment.id"
                                            class="bg-gray-50 p-3 rounded"
                                        >
                                            <span class="font-semibold text-sm">{{ comment.user.name }}:</span>
                                            <p class="text-gray-700 text-sm mt-1">{{ comment.title }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-else class="text-gray-500 italic">
                            {{ assignment.receiver.name }} još nije uneo/la svoje želje.
                        </div>
                    </div>
                </div>

                <!-- Gift Report Form (for receiver) -->
                <div v-if="$page.props.auth.user.id === assignment.receiver_id" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-4">Šta si dobio/la?</h3>
                        <p class="text-gray-600 mb-4">
                            Nakon što dobiješ poklon, ovde možeš uneti šta ti je pokloneno i ostaviti feedback.
                        </p>

                        <form @submit.prevent="submitGift" class="space-y-4">
                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">
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
                                <div v-if="giftForm.errors.description" class="text-red-600 text-sm mt-1">
                                    {{ giftForm.errors.description }}
                                </div>
                            </div>

                            <!-- Satisfaction -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
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
                                <div v-if="giftForm.errors.is_satisfied" class="text-red-600 text-sm mt-1">
                                    {{ giftForm.errors.is_satisfied }}
                                </div>
                            </div>

                            <!-- Feedback -->
                            <div>
                                <label for="feedback" class="block text-sm font-medium text-gray-700">
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
                                    {{ assignment.gift ? 'Ažuriraj' : 'Sačuvaj' }} Poklon
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
