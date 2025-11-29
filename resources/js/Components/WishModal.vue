<script setup>
import { useForm } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
    show: {
        type: Boolean,
        required: true,
    },
    wish: {
        type: Object,
        default: null,
    },
    eventId: {
        type: Number,
        default: null,
    },
});

const emit = defineEmits(['close']);

const form = useForm({
    want: props.wish?.want || "",
    does_not_want: props.wish?.does_not_want || "",
    event_id: props.eventId || null,
});

const closeModal = () => {
    form.clearErrors();
    form.reset();
    emit('close');
};

const saveWish = () => {
    form.post(route("wishes.store"), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
    });
};

// Watch for wish prop changes
import { watch } from 'vue';
watch(() => props.wish, (newWish) => {
    if (newWish) {
        form.want = newWish.want || "";
        form.does_not_want = newWish.does_not_want || "";
    }
}, { deep: true });
</script>

<template>
    <Modal :show="show" @close="closeModal">
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

                    <InputError :message="form.errors.want" class="mt-2" />
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
</template>
