<script setup>
import { ref } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    event: Object,
    userParticipating: Boolean,
    canRegister: Boolean,
    isAdmin: Boolean,
});

const form = useForm({
    description: '',
    is_satisfied: true,
    feedback: '',
});

const makeAssignments = () => {
    if (confirm(`Da li ste sigurni da želite da izvršite random dodelu za ${props.event.participants_count} učesnika?`)) {
        router.post(route('assignments.make', props.event.id), {}, {
            preserveScroll: true,
        });
    }
};

const registerForEvent = () => {
    router.post(route('events.register', props.event.id), {}, {
        preserveScroll: true,
    });
};

const unregisterFromEvent = () => {
    if (confirm('Da li ste sigurni da želite da se odjavite?')) {
        router.delete(route('events.unregister', props.event.id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head :title="`Secret Santa ${event.year}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ event.name || `Secret Santa ${event.year}` }}
                </h2>
                <Link
                    :href="route('events.index')"
                    class="text-gray-600 hover:text-gray-900"
                >
                    ← Nazad na listu
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Event Info Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <h3 class="text-2xl font-bold">{{ event.name || `Secret Santa ${event.year}` }}</h3>
                            <span
                                v-if="event.is_active"
                                class="bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full"
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

                        <p v-if="event.description" class="text-gray-700 mb-6">
                            {{ event.description }}
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="bg-gray-50 p-4 rounded">
                                <div class="text-sm text-gray-600">Prijave</div>
                                <div class="text-lg font-semibold">
                                    {{ event.registration_start ? new Date(event.registration_start).toLocaleDateString('sr-RS') : 'N/A' }}
                                    -
                                    {{ event.registration_end ? new Date(event.registration_end).toLocaleDateString('sr-RS') : 'N/A' }}
                                </div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded">
                                <div class="text-sm text-gray-600">Broj učesnika</div>
                                <div class="text-lg font-semibold">{{ event.participants?.length || 0 }}</div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded">
                                <div class="text-sm text-gray-600">Datum dodele</div>
                                <div class="text-lg font-semibold">
                                    {{ event.assignment_date ? new Date(event.assignment_date).toLocaleDateString('sr-RS') : 'Nije izvršena' }}
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-4">
                            <button
                                v-if="canRegister && !userParticipating"
                                @click="registerForEvent"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                            >
                                Prijavi se
                            </button>

                            <button
                                v-if="userParticipating && !event.assignments_made"
                                @click="unregisterFromEvent"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                            >
                                Odjavi se
                            </button>

                            <span
                                v-if="userParticipating"
                                class="inline-flex items-center bg-blue-100 text-blue-800 text-sm font-medium px-4 py-2 rounded"
                            >
                                ✓ Prijavljeni ste
                            </span>

                            <button
                                v-if="isAdmin && !event.assignments_made && event.participants?.length >= 2"
                                @click="makeAssignments"
                                class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded"
                            >
                                Izvuci Parove
                            </button>

                            <Link
                                v-if="event.assignments_made && userParticipating"
                                :href="route('assignments.my', event.id)"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                            >
                                Vidi Kome Kupuješ
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Participants List -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-4">Učesnici ({{ event.participants?.length || 0 }})</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div
                                v-for="participant in event.participants"
                                :key="participant.id"
                                class="p-3 border rounded-lg"
                            >
                                <div class="font-semibold">{{ participant.name }}</div>
                                <div class="text-sm text-gray-600">
                                    Prijavljen: {{ new Date(participant.pivot.registered_at).toLocaleDateString('sr-RS') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Wishes for this event -->
                <div v-if="event.wishes && event.wishes.length > 0" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-4">Želje učesnika</h3>
                        <div class="space-y-4">
                            <div
                                v-for="wish in event.wishes"
                                :key="wish.id"
                                class="p-4 border rounded-lg"
                            >
                                <h4 class="font-semibold mb-2">{{ wish.user.name }}</h4>
                                <div v-if="wish.want" class="mb-2">
                                    <span class="text-green-600 font-semibold">Voli:</span>
                                    <p class="text-gray-700">{{ wish.want }}</p>
                                </div>
                                <div v-if="wish.does_not_want">
                                    <span class="text-red-600 font-semibold">Ne voli:</span>
                                    <p class="text-gray-700">{{ wish.does_not_want }}</p>
                                </div>

                                <!-- Comments -->
                                <div v-if="wish.comments && wish.comments.length > 0" class="mt-3 space-y-2">
                                    <div class="text-sm font-semibold text-gray-600">Sugestije:</div>
                                    <div
                                        v-for="comment in wish.comments"
                                        :key="comment.id"
                                        class="text-sm bg-gray-50 p-2 rounded"
                                    >
                                        <span class="font-semibold">{{ comment.user.name }}:</span>
                                        {{ comment.title }}
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
