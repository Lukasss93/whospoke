<script setup lang="ts">
import Modal from "@/Components/Modal.vue";
import CancelIcon from "@/Components/CancelIcon.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import PlusIcon from "@/Components/PlusIcon.vue";
import {ref} from "vue";
import {useDark} from "@vueuse/core";

const isDark = useDark();

withDefaults(defineProps<{
    show?: boolean;
}>(), {
    show: false,
});

const code = defineModel<string>('code', {default: ''});
const members = defineModel<string[]>('members', {default: []});
const errors = defineModel<Partial<Record<"code" | "members", string>>>('errors', {default: {}});

defineEmits(['close', 'save']);

const newMember = ref<string>('');
const removeMember = (index: number) => members.value.splice(index, 1);
const addNewMember = () => {
    if (newMember.value.trim() === '') {
        return;
    }

    members.value.push(newMember.value);
    newMember.value = '';
};

function getFirstArrayError(errors: Record<string, string>, key: string): string | null {
    const errorKey = Object.keys(errors).find(i => i.startsWith(key + '.'));
    return errorKey ? errors[errorKey] : null;
}

</script>

<template>
    <Modal :show="show" @close="$emit('close')">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Crea una sessione
            </h2>

            <p class="mt-1 text-gray-600 dark:text-gray-400">
                Inserisci il codice della sessione che vuoi creare
            </p>
            <input type="text"
                   v-model="code"
                   class="mt-1 w-full border border-gray-600 rounded-md p-2 dark:bg-gray-900 dark:text-white"
                   placeholder="Codice sessione"/>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-500 italic">
                Verrà usato per generare un link diretto alla sessione
            </p>
            <p class="text-sm text-red-500" v-if="errors.code">
                {{ errors.code }}
            </p>

            <p class="my-2 text-gray-600 dark:text-gray-400">
                Membri della sessione ({{ members.length }}/20)
            </p>

            <div class="flex gap-2 mb-2" v-for="(name,index) in members" :key="index">
                <div class="flex-1">
                    <input type="text"
                           v-model="members[index]"
                           class="w-full border border-gray-600 rounded-md p-2 dark:bg-gray-900 dark:text-white"
                           placeholder="Nome membro"/>
                </div>
                <div>
                    <DangerButton @click="()=>removeMember(index)">
                        <CancelIcon :size="24" color="white"/>
                    </DangerButton>
                </div>
            </div>

            <div class="flex gap-2 mb-2">
                <div class="flex-1">
                    <input type="text"
                           v-model="newMember"
                           @keyup.enter="addNewMember"
                           class="w-full border border-gray-600 rounded-md p-2 dark:bg-gray-900 dark:text-white"
                           placeholder="Nome membro"/>
                </div>
                <div>
                    <PrimaryButton @click="addNewMember">
                        <PlusIcon :size="24" :color="isDark ? 'black' : 'white'"/>
                    </PrimaryButton>
                </div>
            </div>

            <p class="text-sm text-red-500" v-if="errors.members">
                {{ errors.members }}
            </p>

            <p class="text-sm text-red-500" v-if="getFirstArrayError(errors, 'members')">
                {{ getFirstArrayError(errors, 'members') }}
            </p>

            <div class="mt-4 flex justify-end gap-4">
                <button type="button" class="text-red-500 hover:underline"
                        @click="$emit('close')">
                    Annulla
                </button>
                <PrimaryButton @click="$emit('save')">
                    Salva
                </PrimaryButton>
            </div>
        </div>
    </Modal>
</template>

<style scoped>

</style>