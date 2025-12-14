<script setup>
import { ref } from "vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    organization: Object,
    isAdmin: Boolean,
});

const showInviteLink = ref(false);
const inviteLink = ref(
    `${window.location.origin}/organizations/${props.organization.slug}/join`
);

const copyInviteLink = () => {
    navigator.clipboard.writeText(inviteLink.value);
    alert("Link kopiran u clipboard! 📋");
};

const leaveOrganization = () => {
    if (confirm("Da li ste sigurni da želite da napustite ovu organizaciju?")) {
        router.post(route("organizations.leave", props.organization.id));
    }
};

const removeMember = (userId) => {
    if (confirm("Da li ste sigurni da želite da uklonite ovog člana?")) {
        router.delete(
            route("organizations.removeMember", [
                props.organization.id,
                userId,
            ]),
            {
                preserveScroll: true,
            }
        );
    }
};

const updateMemberRole = (userId, currentRole) => {
    const newRole = currentRole === "admin" ? "member" : "admin";
    if (
        confirm(`Da li želite da promenite ulogu u: ${newRole.toUpperCase()}?`)
    ) {
        router.post(
            route("organizations.updateMemberRole", [
                props.organization.id,
                userId,
            ]),
            { role: newRole },
            {
                preserveScroll: true,
            }
        );
    }
};
</script>

<template>
    <Head :title="organization.name" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2
                    class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
                >
                    {{ organization.name }}
                </h2>
                <div class="flex gap-2">
                    <Link
                        v-if="isAdmin"
                        :href="
                            route('events.create', {
                                organization_id: organization.id,
                            })
                        "
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                    >
                        Kreiraj Event 🎄
                    </Link>
                    <Link
                        v-if="isAdmin"
                        :href="route('organizations.edit', organization.id)"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    >
                        Izmeni
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Organization Info -->
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6"
                >
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-800 mb-2">
                                {{ organization.name }}
                            </h3>
                            <p
                                v-if="organization.description"
                                class="text-gray-600 mb-4"
                            >
                                {{ organization.description }}
                            </p>
                            <div class="flex gap-6 text-sm text-gray-500">
                                <span
                                    >👥
                                    {{
                                        organization.members.length
                                    }}
                                    članova</span
                                >
                                <span
                                    >🎄
                                    {{
                                        organization.events.length
                                    }}
                                    evenata</span
                                >
                                <span
                                    >👑 Vlasnik:
                                    {{ organization.owner.name }}</span
                                >
                            </div>
                        </div>
                        <div
                            v-if="
                                organization.owner_id !==
                                $page.props.auth.user.id
                            "
                        >
                            <button
                                @click="leaveOrganization"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm"
                            >
                                Napusti Organizaciju
                            </button>
                        </div>
                    </div>

                    <!-- Invite Link -->
                    <div v-if="isAdmin" class="mt-6 pt-6 border-t">
                        <button
                            @click="showInviteLink = !showInviteLink"
                            class="text-blue-600 hover:text-blue-800 font-semibold mb-2"
                        >
                            {{ showInviteLink ? "▼" : "▶" }} Invite Link
                        </button>
                        <div
                            v-if="showInviteLink"
                            class="bg-gray-50 p-4 rounded mt-2"
                        >
                            <p class="text-sm text-gray-600 mb-2">
                                Podelite ovaj link sa kolegama da se pridruže
                                organizaciji:
                            </p>
                            <div class="flex gap-2">
                                <input
                                    type="text"
                                    :value="inviteLink"
                                    readonly
                                    class="flex-1 border-gray-300 rounded-md text-sm"
                                />
                                <button
                                    @click="copyInviteLink"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                >
                                    Kopiraj
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Events List -->
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6"
                >
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Eventi</h3>
                    <div
                        v-if="organization.events.length > 0"
                        class="space-y-3"
                    >
                        <Link
                            v-for="event in organization.events"
                            :key="event.id"
                            :href="route('events.show', event.id)"
                            class="block p-4 border rounded hover:bg-gray-50 transition"
                        >
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="font-semibold text-gray-800">
                                        {{
                                            event.name ||
                                            `Secret Santa ${event.year}`
                                        }}
                                        <span
                                            v-if="event.is_active"
                                            class="bg-green-500 text-white text-xs px-2 py-1 rounded ml-2"
                                        >
                                            AKTIVAN
                                        </span>
                                    </h4>
                                    <p class="text-sm text-gray-600">
                                        {{ event.participants_count }} učesnika
                                    </p>
                                </div>
                                <span class="text-gray-500">{{
                                    event.year
                                }}</span>
                            </div>
                        </Link>
                    </div>
                    <p v-else class="text-gray-600">Nema kreiranih evenata.</p>
                </div>

                <!-- Members List -->
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6"
                >
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        Članovi
                    </h3>
                    <div class="space-y-2">
                        <div
                            v-for="member in organization.members"
                            :key="member.id"
                            class="flex justify-between items-center p-3 border rounded"
                        >
                            <div class="flex items-center gap-3">
                                <div>
                                    <p class="font-semibold text-gray-800">
                                        {{ member.name }}
                                        <span
                                            v-if="
                                                member.id ===
                                                organization.owner_id
                                            "
                                            class="text-xs text-yellow-600"
                                        >
                                            👑 Owner
                                        </span>
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ member.email }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span
                                    :class="
                                        member.pivot.role === 'admin'
                                            ? 'bg-green-100 text-green-800'
                                            : 'bg-gray-100 text-gray-800'
                                    "
                                    class="text-xs font-semibold px-2 py-1 rounded uppercase"
                                >
                                    {{ member.pivot.role }}
                                </span>
                                <div
                                    v-if="
                                        isAdmin &&
                                        member.id !== organization.owner_id
                                    "
                                    class="flex gap-1"
                                >
                                    <button
                                        @click="
                                            updateMemberRole(
                                                member.id,
                                                member.pivot.role
                                            )
                                        "
                                        class="text-blue-600 hover:text-blue-800 text-sm px-2 py-1"
                                        title="Promeni ulogu"
                                    >
                                        🔄
                                    </button>
                                    <button
                                        @click="removeMember(member.id)"
                                        class="text-red-600 hover:text-red-800 text-sm px-2 py-1"
                                        title="Ukloni člana"
                                    >
                                        ❌
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
