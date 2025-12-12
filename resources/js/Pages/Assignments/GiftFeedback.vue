<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    assignment: Object,
    event: Object,
});
</script>

<template>
    <Head title="Feedback o Poklonu" />

    <AuthenticatedLayout :active-event="event">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Feedback o Poklonu
                </h2>
                <Link
                    :href="route('events.show', event.id)"
                    class="text-gray-600 hover:text-gray-900 dark:text-gray-200 dark:hover:text-white"
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
                        <h3 class="text-2xl font-bold mb-4">
                            🎁 Poklon za {{ assignment.receiver.name }}
                        </h3>
                    </div>
                </div>

                <!-- Gift Feedback Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div v-if="assignment.gift">
                            <h3
                                class="text-xl font-bold mb-6 flex items-center"
                            >
                                <span class="mr-2">📝</span>
                                Šta je {{ assignment.receiver.name }} dobio/la
                            </h3>

                            <!-- Gift Description -->
                            <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                                <h4 class="font-semibold text-gray-700 mb-2">
                                    🎁 Poklon:
                                </h4>
                                <p class="text-gray-800 text-lg">
                                    {{ assignment.gift.description }}
                                </p>
                            </div>

                            <!-- Satisfaction -->
                            <div
                                class="mb-6 p-4 rounded-lg border"
                                :class="{
                                    'bg-green-50 border-green-200':
                                        assignment.gift.is_satisfied,
                                    'bg-orange-50 border-orange-200':
                                        !assignment.gift.is_satisfied,
                                }"
                            >
                                <h4
                                    class="font-semibold mb-2"
                                    :class="{
                                        'text-green-700':
                                            assignment.gift.is_satisfied,
                                        'text-orange-700':
                                            !assignment.gift.is_satisfied,
                                    }"
                                >
                                    {{
                                        assignment.gift.is_satisfied
                                            ? "😊"
                                            : "😐"
                                    }}
                                    Zadovoljstvo:
                                </h4>
                                <p
                                    :class="{
                                        'text-green-800':
                                            assignment.gift.is_satisfied,
                                        'text-orange-800':
                                            !assignment.gift.is_satisfied,
                                    }"
                                    class="text-lg font-medium"
                                >
                                    {{
                                        assignment.gift.is_satisfied
                                            ? "Vrlo je zadovoljan/na!"
                                            : "Nije baš ono što je očekivao/la"
                                    }}
                                </p>
                            </div>

                            <!-- Feedback Comment -->
                            <div
                                v-if="assignment.gift.feedback"
                                class="mb-6 p-4 bg-purple-50 border border-purple-200 rounded-lg"
                            >
                                <h4 class="font-semibold text-purple-700 mb-2">
                                    💬 Komentar:
                                </h4>
                                <p class="text-purple-800 whitespace-pre-line">
                                    {{ assignment.gift.feedback }}
                                </p>
                            </div>

                            <!-- Received Date -->
                            <div
                                v-if="assignment.gift.received_at"
                                class="text-sm text-gray-500"
                            >
                                <span class="font-semibold">Uneseno:</span>
                                {{
                                    new Date(
                                        assignment.gift.received_at ||
                                            assignment.gift.created_at
                                    ).toLocaleString("sr-RS")
                                }}
                            </div>
                        </div>

                        <!-- No Gift Feedback Yet -->
                        <div v-else class="text-center py-12">
                            <div class="text-6xl mb-4">🎁</div>
                            <h3
                                class="text-xl font-semibold text-gray-700 mb-2"
                            >
                                {{ assignment.receiver.name }} još nije uneo/la
                                feedback o poklonu
                            </h3>
                            <p class="text-gray-500">
                                Kada primi poklon i unese svoje utiske, videćeš
                                ih ovde.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tips Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-700">
                            💡 Saveti:
                        </h3>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex items-start">
                                <span class="mr-2">•</span>
                                <span
                                    >Feedback pomaže da sledeće godine bude još
                                    bolje</span
                                >
                            </div>
                            <div class="flex items-start">
                                <span class="mr-2">•</span>
                                <span
                                    >Ne brini ako osoba nije zadovoljna - važno
                                    je učešće!</span
                                >
                            </div>
                            <div class="flex items-start">
                                <span class="mr-2">•</span>
                                <span
                                    >Ova informacija je privatna između tebe i
                                    primaoca</span
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
