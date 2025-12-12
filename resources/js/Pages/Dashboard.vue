<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage } from "@inertiajs/vue3";
import WishModal from "@/Components/WishModal.vue";
import CommentModal from "@/Components/CommentModal.vue";
import { ref, computed } from "vue";

const props = defineProps({
    users: {
        type: Array,
    },
    activeEvent: {
        type: Object,
    },
});

const searchQuery = ref("");

const transliterateToLatin = (text) => {
    const cyrillicToLatinMap = {
        а: "a",
        б: "b",
        в: "v",
        г: "g",
        д: "d",
        ђ: "đ",
        е: "e",
        ж: "ž",
        з: "z",
        и: "i",
        ј: "j",
        к: "k",
        л: "l",
        љ: "lj",
        м: "m",
        н: "n",
        њ: "nj",
        о: "o",
        п: "p",
        р: "r",
        с: "s",
        т: "t",
        ћ: "ć",
        у: "u",
        ф: "f",
        х: "h",
        ц: "c",
        ч: "č",
        џ: "dž",
        ш: "š",
        А: "A",
        Б: "B",
        В: "V",
        Г: "G",
        Д: "D",
        Ђ: "Đ",
        Е: "E",
        Ж: "Ž",
        З: "Z",
        И: "I",
        Ј: "J",
        К: "K",
        Л: "L",
        Љ: "Lj",
        М: "M",
        Н: "N",
        Њ: "Nj",
        О: "O",
        П: "P",
        Р: "R",
        С: "S",
        Т: "T",
        Ћ: "Ć",
        У: "U",
        Ф: "F",
        Х: "H",
        Ц: "C",
        Ч: "Č",
        Џ: "Dž",
        Ш: "Š",
    };

    return text
        .split("")
        .map((char) => cyrillicToLatinMap[char] || char)
        .join("");
};

const transliterateToCyrillic = (text) => {
    const latinToCyrillicMap = {
        a: "а",
        b: "б",
        v: "в",
        g: "г",
        d: "д",
        đ: "ђ",
        e: "е",
        ž: "ж",
        z: "з",
        i: "и",
        j: "ј",
        k: "к",
        l: "л",
        lj: "љ",
        m: "м",
        n: "н",
        nj: "њ",
        o: "о",
        p: "п",
        r: "р",
        s: "с",
        t: "т",
        ć: "ћ",
        u: "у",
        f: "ф",
        h: "х",
        c: "ц",
        č: "ч",
        dž: "џ",
        š: "ш",
        A: "А",
        B: "Б",
        V: "В",
        G: "Г",
        D: "Д",
        Đ: "Ђ",
        E: "Е",
        Ž: "Ж",
        Z: "З",
        I: "И",
        J: "Ј",
        K: "К",
        L: "Л",
        Lj: "Љ",
        M: "М",
        N: "Н",
        Nj: "Њ",
        O: "О",
        P: "П",
        R: "Р",
        S: "С",
        T: "Т",
        Ć: "Ћ",
        U: "У",
        F: "Ф",
        H: "Х",
        C: "Ц",
        Č: "Ч",
        Dž: "Џ",
        Š: "Ш",
    };

    return text
        .split("")
        .map((char) => latinToCyrillicMap[char] || char)
        .join("");
};

const filteredUsers = computed(() => {
    const query = searchQuery.value.toLowerCase();

    return props.users.filter((user) => {
        const name = user.name.toLowerCase();
        const nameAsLatin = transliterateToLatin(name);
        const nameAsCyrillic = transliterateToCyrillic(name);

        const queryAsLatin = transliterateToLatin(query);
        const queryAsCyrillic = transliterateToCyrillic(query);

        return (
            name.includes(query) ||
            nameAsLatin.includes(queryAsLatin) ||
            nameAsCyrillic.includes(queryAsCyrillic)
        );
    });
});

const logedUser = usePage().props.auth.user;

// Wish Modal
const editingWish = ref(false);
const currentWish = ref(null);

const editWish = (wish) => {
    currentWish.value = wish;
    editingWish.value = true;
    console.log("Editing wish:", wish);
};

const closeWishModal = () => {
    editingWish.value = false;
    currentWish.value = null;
};

// Comment Modal
const editingComment = ref(false);
const currentReceiverId = ref(null);
const currentComments = ref([]);

const openCommentDialog = (user) => {
    currentReceiverId.value = user.id;
    currentComments.value = user.received_comments || [];
    editingComment.value = true;
};

const closeCommentModal = () => {
    editingComment.value = false;
    currentReceiverId.value = null;
    currentComments.value = [];
};
</script>

<template>
    <WishModal 
        :show="editingWish" 
        :wish="currentWish" 
        :event-id="activeEvent?.id"
        @close="closeWishModal" 
    />

    <CommentModal 
        :show="editingComment" 
        :comments="currentComments"
        :event-id="activeEvent?.id"
        :receiver-id="currentReceiverId"
        @close="closeCommentModal" 
    />

    <Head title="Mega" />

    <AuthenticatedLayout :active-event="props.activeEvent">
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200 text-center"
                style="font-family: Lobster"
            >
                Lista želja i nepoželjnih poklona – olakšaj svom tajnom Deda
                Mrazu posao da ne pogreši! 🎅 🎄 🎁
            </h2>
        </template>

        <div v-if="!props.users.some(user => user.id === logedUser.id)" class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-24 h-24 mx-auto text-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">
                            Niste prijavljeni za Secret Santa događaj
                        </h3>
                        <p class="text-gray-600 mb-6">
                            Da biste videli listu želja i učestvovali u razmeni poklona, 
                            prvo se prijavite za aktivan Secret Santa događaj.
                        </p>
                        <a 
                            :href="route('events.show', activeEvent.id)" 
                            class="inline-block bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg"
                        >
                            🎅 Prijavi se za Secret Santa
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div
            v-else
            class="overflow-x-auto m-5 min-h-screen bg-contain bg-center bg-no-repeat bg-[url('/images/cats.png')]"
            style="background-color: #fefae8"
        >
            <div class="mt-2">
                <div class="relative w-full max-w-md mx-auto">
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Pronađi kolegu..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    />
                    <div
                        class="absolute inset-y-0 left-0 flex items-center pl-3"
                    >
                        <svg
                            class="w-5 h-5 text-gray-400"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M11 4a7 7 0 017 7m-1.41 3.59a7 7 0 10-9.9-9.9 7 7 0 009.9 9.9zm0 0L21 21"
                            />
                        </svg>
                    </div>
                </div>
            </div>
            <table
                class="min-w-3/4 w-3/4 my-5 mx-auto border-collapse border border-gray-200 shadow-lg rounded-3xl opacity-80"
            >
                <thead class="bg-blue-900 text-white">
                    <tr
                        style="
                            background: repeating-linear-gradient(
                                45deg,
                                #5cc48a,
                                #5cc48a 30px,
                                #fff 30px,
                                #fff 60px,
                                #ef3d3d 60px,
                                #ef3d3d 90px,
                                #fff 90px,
                                #fff 120px
                            );
                        "
                    >
                        <th
                            class="px-6 py-3 text-center text-black text-lg font-black uppercase"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="size-6"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"
                                />
                            </svg>
                        </th>
                        <th
                            class="px-6 py-3 text-center text-lg font-black uppercase w-1/3"
                        >
                            🎁
                        </th>
                        <th
                            class="px-6 py-3 text-center text-lg font-black uppercase w-1/3"
                        >
                            🚫
                        </th>
                        <th class="px-6 py-3 text-center uppercase text-black">
                            Pomoć kolege
                        </th>
                        <th
                            class="px-6 py-3 text-center text-lg font-black uppercase"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="black"
                                viewBox="0 0 24 24"
                                stroke-width="2"
                                stroke="currentColor"
                                class="w-6 h-6"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L8.75 19.25a2.5 2.5 0 01-1.061.659l-4.25 1.061a0.5 0.5 0 01-.612-.612l1.061-4.25a2.5 2.5 0 01.659-1.061L15.232 5.232z"
                                />
                            </svg>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr
                        v-for="user in filteredUsers"
                        :key="user.id"
                        class="hover:bg-gray-100"
                    >
                        <td class="px-6 py-4 text-sm text-black font-black">
                            {{ user.name }} 
                        </td>
                        <td class="px-6 py-4 text-sm text-green-500 font-black">
                            {{ user.wishes[0]?.want }}
                        </td>
                        <td class="px-6 py-4 text-sm text-red-500 font-black">
                            {{ user.wishes[0]?.does_not_want }}
                        </td>
                        <td
                            class="px-6 py-4 text-sm text-red-500 font-black text-center"
                        >
                            <button
                                v-if="user.id !== $page.props.auth.user.id"
                                class="text-blue-500 hover:underline font-black"
                                @click="openCommentDialog(user)"
                            >
                                <div class="relative inline-block">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="size-9"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"
                                        />
                                    </svg>

                                    <span
                                        v-if="user.received_comments?.length > 0"
                                        class="absolute top-0 right-0 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full"
                                    >
                                       {{ user.received_comments?.length }} 
                                    </span>
                                </div>
                            </button>
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-center"
                        >
                            <button
                                v-if="logedUser.id == user.id"
                                class="text-blue-500 hover:underline font-black"
                                @click="editWish(user.wishes[0])"
                            >
                                Unos 
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AuthenticatedLayout>
</template>
