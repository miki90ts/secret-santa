<script setup>
import { Head } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";

defineProps({
    organization: Object,
});
</script>

<template>
    <GuestLayout>
        <Head title="Pridruži se Organizaciji" />

        <div class="mb-4 text-sm text-gray-600">
            Pozvani ste da se pridružite organizaciji:
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">
                {{ organization.name }}
            </h2>
            <p v-if="organization.description" class="text-gray-600">
                {{ organization.description }}
            </p>
            <div class="mt-4 flex gap-6 text-sm text-gray-500">
                <span>👥 {{ organization.members_count }} članova</span>
                <span>🎄 {{ organization.events_count }} evenata</span>
            </div>
        </div>

        <div class="flex items-center justify-end mt-4">
            <a
                :href="route('login')"
                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4"
            >
                Prijavi se da se pridružiš
            </a>
        </div>
    </GuestLayout>
</template>
