<script setup lang="ts">
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import {ref} from "vue";
import {useDark} from "@vueuse/core";
import {trans} from "laravel-translator";
import InputError from "@/Components/InputError.vue";

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

function getFirstArrayError(errors: Record<string, string>, key: string): string {
    const errorKey = Object.keys(errors).find(i => i.startsWith(key + '.'));
    return errorKey ? errors[errorKey] : '';
}

</script>

<template>
    <Modal :show="show" @close="$emit('close')">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ trans('app.create.label') }}
            </h2>

            <p class="mt-1 text-gray-600 dark:text-gray-400">
                {{ trans('app.room.code.title') }}
            </p>
            <input type="text"
                   v-model="code"
                   class="mt-1 w-full border border-gray-600 rounded-md p-2 dark:bg-gray-900 dark:text-white"
                   :placeholder="trans('app.room.code.placeholder')"/>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-500 italic">
                {{ trans('app.room.code.info') }}
            </p>
            <InputError :message="errors.code"/>

            <p class="my-2 text-gray-600 dark:text-gray-400">
                {{ trans('app.room.members.title') }} ({{ members.length }}/20)
            </p>

            <div class="flex gap-2 mb-2" v-for="(name,index) in members" :key="index">
                <div class="flex-1">
                    <input type="text"
                           v-model="members[index]"
                           class="w-full border border-gray-600 rounded-md p-2 dark:bg-gray-900 dark:text-white"
                           :placeholder="trans('app.room.members.placeholder')"/>
                </div>
                <div>
                    <DangerButton @click="()=>removeMember(index)">
                        <font-awesome-icon fixed-width icon="fa-solid fa-xmark" size="2x"/>
                    </DangerButton>
                </div>
            </div>

            <div class="flex gap-2 mb-2">
                <div class="flex-1">
                    <input type="text"
                           v-model="newMember"
                           @keyup.enter="addNewMember"
                           class="w-full border border-gray-600 rounded-md p-2 dark:bg-gray-900 dark:text-white"
                           :placeholder="trans('app.room.members.placeholder')"/>
                </div>
                <div>
                    <PrimaryButton @click="addNewMember">
                        <font-awesome-icon fixed-width icon="fa-solid fa-plus" size="2x"/>
                    </PrimaryButton>
                </div>
            </div>

            <InputError :message="errors.members"/>
            <InputError :message="getFirstArrayError(errors, 'members')"/>

            <div class="mt-4 flex justify-end gap-4">
                <button type="button" class="text-red-500 hover:underline"
                        @click="$emit('close')">
                    {{ trans('actions.cancel') }}
                </button>
                <PrimaryButton @click="$emit('save')">
                    {{ trans('actions.save') }}
                </PrimaryButton>
            </div>
        </div>
    </Modal>
</template>

<style scoped>

</style>
