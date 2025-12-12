<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    wishes: Array,
});
</script>

<template>
    <Head title="Istorija Želja" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Moja Istorija Želja
                </h2>
                <Link
                    :href="route('dashboard')"
                    class="text-gray-600 hover:text-gray-900 dark:text-gray-200 dark:hover:text-white"
                >
                    ← Nazad na Dashboard
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div v-if="wishes.length === 0" class="text-center text-gray-500 py-8">
                            Nemaš još unetih želja. Kreiraj svoju prvu listu želja za aktivan događaj!
                        </div>

                        <div v-else class="space-y-6">
                            <div
                                v-for="wish in wishes"
                                :key="wish.id"
                                class="border rounded-lg p-5"
                            >
                                <!-- Event Info -->
                                <div class="flex items-center gap-3 mb-4">
                                    <h3 class="text-xl font-bold">
                                        {{ wish.event.name || `Secret Santa ${wish.event.year}` }}
                                    </h3>
                                    <span class="bg-gray-200 text-gray-700 text-xs font-bold px-2 py-1 rounded">
                                        {{ wish.event.year }}
                                    </span>
                                </div>

                                <!-- Wish Content -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div v-if="wish.want" class="bg-green-50 p-4 rounded">
                                        <h4 class="text-green-700 font-semibold mb-2">✓ Voleo/la bih:</h4>
                                        <p class="text-gray-700 whitespace-pre-line">{{ wish.want }}</p>
                                    </div>

                                    <div v-if="wish.does_not_want" class="bg-red-50 p-4 rounded">
                                        <h4 class="text-red-700 font-semibold mb-2">✗ Ne bih voleo/la:</h4>
                                        <p class="text-gray-700 whitespace-pre-line">{{ wish.does_not_want }}</p>
                                    </div>
                                </div>

                                <!-- Comments / Suggestions -->
                                <div v-if="wish.comments && wish.comments.length > 0" class="mt-4">
                                    <h4 class="font-semibold mb-2 text-gray-700">
                                        💬 Sugestije kolega ({{ wish.comments.length }}):
                                    </h4>
                                    <div class="space-y-2">
                                        <div
                                            v-for="comment in wish.comments"
                                            :key="comment.id"
                                            class="bg-gray-50 p-3 rounded"
                                        >
                                            <span class="font-semibold text-sm">{{ comment.user.name }}:</span>
                                            <p class="text-gray-700 text-sm mt-1">{{ comment.title }}</p>
                                            <span class="text-xs text-gray-500">
                                                {{ new Date(comment.created_at).toLocaleDateString('sr-RS') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Timestamp -->
                                <div class="text-xs text-gray-500 mt-4">
                                    Kreirano: {{ new Date(wish.created_at).toLocaleString('sr-RS') }}
                                    <span v-if="wish.updated_at !== wish.created_at">
                                        | Ažurirano: {{ new Date(wish.updated_at).toLocaleString('sr-RS') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
