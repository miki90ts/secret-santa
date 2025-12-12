<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    gifts: Array,
    eventId: Number,
});

const getSatisfactionIcon = (isSatisfied) => {
    return isSatisfied ? '😊' : '😐';
};

const getSatisfactionText = (isSatisfied) => {
    return isSatisfied ? 'Zadovoljan/na' : 'Nije zadovoljan/na';
};

const getSatisfactionColor = (isSatisfied) => {
    return isSatisfied ? 'text-green-600' : 'text-gray-600';
};
</script>

<template>
    <Head title="Pokloni" />

    <AuthenticatedLayout >
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Primljeni Pokloni
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div v-if="gifts.length === 0" class="text-center text-gray-500 py-8">
                            Još nema unetih poklona za ovaj događaj.
                        </div>

                        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div
                                v-for="gift in gifts"
                                :key="gift.id"
                                class="border rounded-lg p-5 hover:shadow-md transition"
                                :class="{ 'border-green-300 bg-green-50': gift.is_satisfied }"
                            >
                                <!-- Receiver Name -->
                                <div class="flex items-center gap-2 mb-3">
                                    <h3 class="text-lg font-semibold">{{ gift.receiver_name }}</h3>
                                    <span class="text-2xl">{{ getSatisfactionIcon(gift.is_satisfied) }}</span>
                                </div>

                                <!-- Gift Description -->
                                <div class="mb-3">
                                    <span class="text-sm text-gray-600 font-medium">Poklon:</span>
                                    <p class="text-gray-800 mt-1">{{ gift.description }}</p>
                                </div>

                                <!-- Satisfaction Status -->
                                <div class="mb-3">
                                    <span
                                        class="text-sm font-semibold"
                                        :class="getSatisfactionColor(gift.is_satisfied)"
                                    >
                                        {{ getSatisfactionText(gift.is_satisfied) }}
                                    </span>
                                </div>

                                <!-- Feedback -->
                                <div v-if="gift.feedback" class="bg-gray-100 p-3 rounded text-sm">
                                    <span class="font-semibold text-gray-700">Komentar:</span>
                                    <p class="text-gray-600 mt-1">{{ gift.feedback }}</p>
                                </div>

                                <!-- Received Date -->
                                <div class="text-xs text-gray-500 mt-3">
                                    Primljeno: {{ new Date(gift.received_at).toLocaleDateString('sr-RS') }}
                                </div>

                                <!-- Note: Giver is hidden - that's the Secret Santa magic! -->
                                <div class="text-xs text-gray-400 italic mt-2">
                                    🎅 Ko je kupio ostaje tajna!
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
