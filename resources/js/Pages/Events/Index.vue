<script setup>
import { ref } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    events: Array,
    activeEvent: Object,
    isAdmin: Boolean,
});

const registerForEvent = (eventId) => {
    router.post(
        route("events.register", eventId),
        {},
        {
            preserveScroll: true,
            onSuccess: () => {
                // Success message će biti prikazan iz flash message
            },
        }
    );
};

const unregisterFromEvent = (eventId) => {
    if (confirm("Da li ste sigurni da želite da se odjavite?")) {
        router.delete(route("events.unregister", eventId), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head title="Secret Santa Događaji" />

    <AuthenticatedLayout :active-event="props.activeEvent">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Secret Santa Događaji
                </h2>
                <Link
                    v-if="isAdmin"
                    :href="route('events.create')"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >
                    Kreiraj Novi Događaj 🎅
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <!-- Active Event -->
                        <div
                            v-if="activeEvent"
                            class="mb-8 p-6 bg-green-50 rounded-lg border-2 border-green-200"
                        >
                            <div class="flex items-center mb-4">
                                <span
                                    class="bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full"
                                >
                                    AKTIVAN
                                </span>
                                <h3 class="text-2xl font-bold ml-4">
                                    {{
                                        activeEvent.name ||
                                        `Secret Santa ${activeEvent.year}`
                                    }}
                                </h3>
                            </div>
                            <p
                                v-if="activeEvent.description"
                                class="text-gray-700 mb-4"
                            >
                                {{ activeEvent.description }}
                            </p>
                            <div
                                class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4"
                            >
                                <div>
                                    <span class="font-semibold"
                                        >Prijave od:</span
                                    >
                                    {{
                                        activeEvent.registration_start
                                            ? new Date(
                                                  activeEvent.registration_start
                                              ).toLocaleDateString("sr-RS")
                                            : "N/A"
                                    }}
                                </div>
                                <div>
                                    <span class="font-semibold"
                                        >Prijave do:</span
                                    >
                                    {{
                                        activeEvent.registration_end
                                            ? new Date(
                                                  activeEvent.registration_end
                                              ).toLocaleDateString("sr-RS")
                                            : "N/A"
                                    }}
                                </div>
                                <div>
                                    <span class="font-semibold"
                                        >Broj učesnika:</span
                                    >
                                    {{ activeEvent.participants_count || 0 }}
                                </div>
                                <div>
                                    <span class="font-semibold"
                                        >Datum razmene poklona:</span
                                    >
                                    {{
                                        activeEvent.exchange_date
                                            ? new Date(
                                                  activeEvent.exchange_date
                                              ).toLocaleDateString("sr-RS")
                                            : "N/A"
                                    }}
                                </div>
                                <div>
                                    <span class="font-semibold"
                                        >Dodele izvršene:</span
                                    >
                                    {{
                                        activeEvent.assignments_made
                                            ? "Da"
                                            : "Ne"
                                    }}
                                </div>
                                <div>
                                    <span class="font-semibold"
                                        >Datum izvlačenja:</span
                                    >
                                    {{
                                        activeEvent.assignment_date
                                            ? new Date(
                                                  activeEvent.assignment_date
                                              ).toLocaleDateString("sr-RS")
                                            : "N/A"
                                    }}
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <Link
                                    :href="route('events.show', activeEvent.id)"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                >
                                    Vidi Detalje
                                </Link>
                            </div>
                        </div>

                        <!-- All Events List -->
                        <h3 class="text-xl font-bold mb-4">Svi Događaji</h3>
                        <div class="space-y-4">
                            <div
                                v-for="event in events"
                                :key="event.id"
                                class="p-4 border rounded-lg hover:shadow-md transition"
                                :class="{
                                    'border-green-300 bg-green-50':
                                        event.is_active,
                                }"
                            >
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3">
                                            <h4 class="text-lg font-semibold">
                                                {{
                                                    event.name ||
                                                    `Secret Santa ${event.year}`
                                                }}
                                            </h4>
                                            <span
                                                v-if="event.is_active"
                                                class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded"
                                            >
                                                AKTIVAN
                                            </span>
                                            <span
                                                v-if="event.assignments_made"
                                                class="bg-purple-500 text-white text-xs font-bold px-2 py-1 rounded"
                                            >
                                                Izvršene dodele
                                            </span>
                                        </div>
                                        <p
                                            v-if="event.description"
                                            class="text-gray-600 text-sm mt-1"
                                        >
                                            {{ event.description }}
                                        </p>
                                        <div class="text-sm text-gray-500 mt-2">
                                            Učesnika:
                                            {{ event.participants_count || 0 }}
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <Link
                                            :href="
                                                route('events.show', event.id)
                                            "
                                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm"
                                        >
                                            Detalji
                                        </Link>
                                        <Link
                                            v-if="isAdmin"
                                            :href="
                                                route('events.edit', event.id)
                                            "
                                            class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded text-sm"
                                        >
                                            Izmeni
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
