<script setup lang="ts">
import {Member} from "@/types";
import Checkbox from "@/Components/Checkbox.vue";
import axios from "axios";
import {toast} from "vue3-toastify";
import {trans} from "laravel-translator";

const props = defineProps<{
    canEdit: boolean;
}>();

const member = defineModel<Member>({required: true});

async function updateMemberStatus(member: Member, status: boolean) {
    // store the old status to revert if the request fails
    const oldStatus = member.status;

    // update the status in the frontend
    member.status = status;

    try {
        // send the request to the server
        await axios.post(route('member.status.update', {member: member.id}), {
            status: status,
        });
    } catch (e) {
        // revert the status if the request fails
        member.status = oldStatus;
        toast.error(trans('app.error'));
    }
}
</script>

<template>
    <div
        class="flex items-center gap-2 w-full bg-gray-300 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 p-1 rounded">
        <div class="flex-1 text-2xl text-black dark:text-white">
            {{ member.name }}
        </div>
        <Checkbox class="size-8" :checked="member.status" :disabled="!canEdit"
                  @change="(e: InputEvent) => updateMemberStatus(member, (e.target as HTMLInputElement).checked)"/>
    </div>
</template>

<style scoped lang="scss">

</style>
