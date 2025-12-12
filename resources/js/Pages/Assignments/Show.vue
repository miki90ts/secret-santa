<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    assignment: Object,
    event: Object,
    comments: Array,
});
</script>

<template>
    <Head title="Moja Dodela" />

    <AuthenticatedLayout :active-event="event">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Moja Secret Santa Dodela
                </h2>
                <Link
                    :href="route('events.show', event.id)"
                    class="text-gray-600 hover:text-gray-900 dark:text-gray-200 dark:hover:text-white"
                >
                    ← Nazad na događaj
                </Link>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-2">
                <!-- Exchange Date -->
                <div
                    v-if="event.exchange_date"
                    class="mt-2 p-4 bg-blue-50 border border-blue-200 rounded-lg"
                >
                    <h4 class="font-semibold text-blue-800 mb-2">
                        📅 Datum razmene poklona:
                    </h4>
                    <p class="text-blue-700">
                        {{
                            new Date(event.exchange_date).toLocaleString(
                                "sr-RS"
                            )
                        }}
                    </p>
                </div>
                <!-- Receiver Info Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-2xl font-bold mb-4">
                            🎁 Kupuješ poklon za: {{ assignment.receiver.name }}
                        </h3>

                        <!-- Wishes -->
                        <div
                            v-if="
                                assignment.receiver.wishes &&
                                assignment.receiver.wishes.length > 0
                            "
                            class="space-y-4"
                        >
                            <div
                                v-for="wish in assignment.receiver.wishes"
                                :key="wish.id"
                                class="border rounded-lg p-4"
                            >
                                <div v-if="wish.want" class="mb-3">
                                    <h4
                                        class="text-green-600 font-semibold mb-2"
                                    >
                                        ✓ Šta voli:
                                    </h4>
                                    <p
                                        class="text-gray-700 whitespace-pre-line"
                                    >
                                        {{ wish.want }}
                                    </p>
                                </div>

                                <div v-if="wish.does_not_want" class="mb-3">
                                    <h4 class="text-red-600 font-semibold mb-2">
                                        ✗ Šta ne voli:
                                    </h4>
                                    <p
                                        class="text-gray-700 whitespace-pre-line"
                                    >
                                        {{ wish.does_not_want }}
                                    </p>
                                </div>

                                <!-- Comments / Suggestions -->
                                <div
                                    v-if="comments && comments.length > 0"
                                    class="mt-4"
                                >
                                    <h4
                                        class="font-semibold mb-2 text-gray-700"
                                    >
                                        💡 Sugestije kolega:
                                    </h4>
                                    <div class="space-y-2">
                                        <div
                                            v-for="comment in comments"
                                            :key="comment.id"
                                            class="bg-gray-50 p-3 rounded"
                                        >
                                            <span class="font-semibold text-sm"
                                                >{{ comment.user.name }}:</span
                                            >
                                            <p
                                                class="text-gray-700 text-sm mt-1"
                                            >
                                                {{ comment.title }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-else class="text-gray-500 italic">
                            {{ assignment.receiver.name }} još nije uneo/la
                            svoje želje.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
