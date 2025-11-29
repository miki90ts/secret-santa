<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
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
    comments: {
        type: Array,
        default: () => [],
    },
    eventId: {
        type: Number,
        default: null,
    },
    receiverId: {
        type: Number,
        default: null,
    },
});

const emit = defineEmits(['close']);

const logedUser = usePage().props.auth.user;

const form = useForm({
    title: "",
    event_id: props.eventId,
    receiver_id: props.receiverId,
});

const closeModal = () => {
    form.title = "";
    form.clearErrors();
    emit('close');
};

const saveComment = () => {
    form.post(route("comment.store"), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
    });
};

// Watch for prop changes
import { watch } from 'vue';
watch(() => [props.eventId, props.receiverId], ([newEventId, newReceiverId]) => {
    form.event_id = newEventId;
    form.receiver_id = newReceiverId;
});
</script>

<template>
    <Modal :show="show" @close="closeModal">
        <div v-if="comments.length > 0">
            <div
                v-for="comment in comments"
                :key="comment.id"
                class="bg-white border border-gray-300 rounded-lg shadow-md p-4 m-4"
            >
                <div class="flex justify-between items-center mb-2">
                    <div
                        v-if="logedUser.id !== receiverId"
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
            v-if="logedUser.id !== receiverId"
        >
            <div class="p-4">
                <div>
                    <InputLabel
                        for="title"
                        value="Podelite šta vaš kolega voli ili priželjkuje!"
                    />
                 
                    <textarea
                        id="title"
                        v-model="form.title"
                        rows="4"
                        class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                        placeholder="Unesite..."
                        required
                    ></textarea>

                    <InputError
                        :message="form.errors.title"
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
