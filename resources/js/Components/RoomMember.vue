<script setup lang="ts">
import {Member} from "@/types";
import Checkbox from "@/Components/Checkbox.vue";
import axios from "axios";
import {toast} from "vue3-toastify";
import {trans} from "laravel-translator";
import {Tippy} from "vue-tippy";

const props = defineProps<{
    canEdit: boolean;
}>();

const member = defineModel<Member>({required: true});

async function updateStatus(status: boolean) {
    // store the old status to revert if the request fails
    const oldStatus = member.value.status;

    // update the status in the frontend
    member.value.status = status;

    try {
        // send the request to the server
        await axios.post(route('member.status.update', {member: member.value.id}), {
            status: status,
        });
    } catch (e) {
        // revert the status if the request fails
        member.value.status = oldStatus;
        toast.error(trans('app.error'));
    }
}

async function updateOffline() {
    // store the old status to revert if the request fails
    const oldOffline = member.value.offline;

    // update the status in the frontend
    member.value.offline = !member.value.offline;

    try {
        // send the request to the server
        await axios.post(route('member.offline.update', {member: member.value.id}), {
            offline: member.value.offline,
        });
    } catch (e) {
        // revert the status if the request fails
        member.value.offline = oldOffline;
        toast.error(trans('app.error'));
    }
}
</script>

<template>
    <div
        class="flex items-center gap-1 w-full bg-gray-300 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 p-1 rounded">

        <tippy>
            <template #content>
                <span v-if="member.offline" class="text-green-500 font-bold">
                    {{ trans('app.member.status.unset') }}
                </span>
                <span v-if="!member.offline" class="text-red-500 font-bold">
                    {{ trans('app.member.status.set') }}
                </span>
            </template>

            <button v-if="canEdit" @click="updateOffline"
                    class="text-black dark:text-white bg-black/20 dark:bg-black/50 p-1 rounded">
                <font-awesome-icon v-if="member.offline" icon="fa-solid fa-eye"/>
                <font-awesome-icon v-if="!member.offline" icon="fa-solid fa-eye-slash"/>
            </button>
        </tippy>
        <div class="flex-1 text-2xl text-black dark:text-white">
            {{ member.name }}
        </div>
        <Checkbox class="size-8" :checked="member.status" :disabled="!canEdit" v-if="!member.offline"
                  @change="(e: InputEvent) => updateStatus((e.target as HTMLInputElement).checked)"/>
        <span v-if="member.offline" class="uppercase text-red-600 font-bold">
            {{ trans('app.member.status.offline') }}
        </span>
    </div>
</template>

<style scoped lang="scss">

</style>
