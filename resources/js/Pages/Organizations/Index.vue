<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    organizations: Array,
    ownedOrganizations: Array,
});
</script>

<template>
    <Head title="Moje Organizacije" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2
                    class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
                >
                    Moje Organizacije
                </h2>
                <Link
                    :href="route('organizations.create')"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >
                    Kreiraj Novu Organizaciju 🏢
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Owned Organizations -->
                <div v-if="ownedOrganizations.length > 0" class="mb-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        Moje Organizacije (Vlasnik)
                    </h3>
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                    >
                        <Link
                            v-for="org in ownedOrganizations"
                            :key="org.id"
                            :href="route('organizations.show', org.id)"
                            class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition-shadow border-2 border-blue-200"
                        >
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-lg font-semibold text-gray-800">
                                    {{ org.name }}
                                </h4>
                                <span
                                    class="bg-blue-500 text-white text-xs px-2 py-1 rounded"
                                    >OWNER</span
                                >
                            </div>
                            <p
                                v-if="org.description"
                                class="text-gray-600 text-sm mb-4"
                            >
                                {{ org.description }}
                            </p>
                            <div class="flex gap-4 text-sm text-gray-500">
                                <span>👥 {{ org.members_count }} članova</span>
                                <span>🎄 {{ org.events_count }} evenata</span>
                            </div>
                        </Link>
                    </div>
                </div>

                <!-- Member Organizations -->
                <div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        Član Organizacija
                    </h3>
                    <div
                        v-if="organizations.length > 0"
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                    >
                        <Link
                            v-for="org in organizations"
                            :key="org.id"
                            :href="route('organizations.show', org.id)"
                            class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition-shadow"
                        >
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-lg font-semibold text-gray-800">
                                    {{ org.name }}
                                </h4>
                                <span
                                    :class="
                                        org.pivot.role === 'admin'
                                            ? 'bg-green-500'
                                            : 'bg-gray-400'
                                    "
                                    class="text-white text-xs px-2 py-1 rounded uppercase"
                                >
                                    {{ org.pivot.role }}
                                </span>
                            </div>
                            <p
                                v-if="org.description"
                                class="text-gray-600 text-sm mb-4"
                            >
                                {{ org.description }}
                            </p>
                            <div class="flex gap-4 text-sm text-gray-500">
                                <span>👥 {{ org.members_count }} članova</span>
                                <span>🎄 {{ org.events_count }} evenata</span>
                            </div>
                        </Link>
                    </div>
                    <div
                        v-else
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6"
                    >
                        <p class="text-gray-600 text-center">
                            Niste član nijedne organizacije. Pridružite se
                            organizaciji pomoću invite linka ili kreirajte
                            svoju!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
