<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import { ref, computed } from "vue";
import DangerButton from "@/Components/DangerButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { useForm } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";

const props = defineProps({
    users: {
        type: Array,
    },
});

const form = useForm({
    want: "",
    does_not_want: "",
});

const formComment = useForm({
    title: "",
    wish_id: "",
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

const editingWish = ref(false);

const closeModal = () => {
    editingWish.value = false;

    form.clearErrors();
    form.reset();
};

const editWish = (wish) => {
    editingWish.value = true;

    form.want = wish.want;
    form.does_not_want = wish.does_not_want;
};

const saveWish = () => {
    form.patch(route("wish.update"), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
    });
};

const currentWish = ref(null);
const editingComment = ref(false);
const currentWishUser = ref(false);

const openCommentDialog = (user) => {
    editingComment.value = true;

    currentWish.value = user.wish;
    formComment.wish_id = user.wish.id;
    currentWishUser.value = user.id;
};

const closeCommentModal = () => {
    currentWish.value = null;
    editingComment.value = false;
    formComment.title = "";
};

const saveComment = () => {
    formComment.post(route("comment.store"), {
        preserveScroll: true,
        onSuccess: () => closeCommentModal(),
    });
};
</script>

<template>
    <Modal :show="editingWish" @close="closeModal">
        <form @submit.prevent="saveWish" class="mt-6 space-y-6">
            <div class="p-6">
                <div class="mt-6">
                    <InputLabel
                        for="want"
                        value=" 🎁 Recite nam šta volite! Pomozite svom tajnom Deda Mrazu da
                            izabere savršen poklon za vas! Navedite šta vas čini srećnim –
                            vaša interesovanja, omiljene hobije, ili specifične stvari koje
                            biste voleli da dobijete."
                    />

                    <textarea
                        id="want"
                        v-model="form.want"
                        rows="4"
                        class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                        placeholder="Unesite..."
                    ></textarea>

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6">
                    <InputLabel
                        for="does_not_want"
                        value="🚫 Recite nam šta da izbegnemo!
                            Da bi poklon bio baš po vašem ukusu, napišite šta ne biste voleli da dobijete – 
                            bilo da su to stvari koje već imate, alergeni, ili nešto što jednostavno nije vaš stil."
                    />

                    <textarea
                        id="does_not_want"
                        v-model="form.does_not_want"
                        rows="4"
                        class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                        placeholder="Unesite..."
                    ></textarea>

                    <InputError
                        :message="form.errors.does_not_want"
                        class="mt-2"
                    />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal">
                        Zatvori
                    </SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Sačuvaj
                    </DangerButton>
                </div>
            </div>
        </form>
    </Modal>

    <Modal :show="editingComment" @close="closeCommentModal">
        <div
            v-if="
                currentWish &&
                currentWish.comments &&
                currentWish.comments.length > 0
            "
        >
            <div
                v-for="comment in currentWish.comments"
                :key="comment.id"
                class="bg-white border border-gray-300 rounded-lg shadow-md p-4 m-4"
            >
                <div class="flex justify-between items-center mb-2">
                    <div
                        v-if="logedUser.id !== currentWishUser"
                        class="text-gray-800 font-semibold text-sm"
                    >
                        {{ comment.user.name }}
                    </div>
                    <div class="text-gray-500 text-sm">
                        {{ comment.created_at }}
                    </div>
                </div>
                <hr />
                <p class="text-gray-700 leading-relaxed text-sm mt-1">
                    {{ comment.title }}
                </p>
            </div>
        </div>
        <form
            @submit.prevent="saveComment"
            class="mt-6 space-y-6"
            v-if="logedUser.id !== currentWishUser"
        >
            <div class="p-4">
                <div>
                    <InputLabel
                        for="title"
                        value="Podelite šta vaš kolega voli ili priželjkuje!"
                    />

                    <textarea
                        id="title"
                        v-model="formComment.title"
                        rows="4"
                        class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                        placeholder="Unesite..."
                        required
                    ></textarea>

                    <InputError
                        :message="formComment.errors.title"
                        class="mt-2"
                    />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeCommentModal">
                        Zatvori
                    </SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': formComment.processing }"
                        :disabled="formComment.processing"
                    >
                        Sačuvaj
                    </DangerButton>
                </div>
            </div>
        </form>
    </Modal>

    <Head title="Mega" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 text-gray-200 text-center"
                style="font-family: Lobster"
            >
                Lista želja i nepoželjnih poklona – olakšaj svom tajnom Deda
                Mrazu posao da ne pogreši! 🎅 🎄 🎁
            </h2>
        </template>

        <div
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
                            {{ user.wish?.want }}
                        </td>
                        <td class="px-6 py-4 text-sm text-red-500 font-black">
                            {{ user.wish?.does_not_want }}
                        </td>
                        <td
                            class="px-6 py-4 text-sm text-red-500 font-black text-center"
                        >
                            <button
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
                                        v-if="user.wish.comments.length > 0"
                                        class="absolute top-0 right-0 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full"
                                    >
                                        {{ user.wish.comments.length }}
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
                                @click="editWish(user.wish)"
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
