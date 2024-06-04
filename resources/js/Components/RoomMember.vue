<script setup lang="ts">
import {Member} from "@/types";
import Checkbox from "@/Components/Checkbox.vue";
import axios from "axios";
import {toast} from "vue3-toastify";
import {trans} from "laravel-translator";
import {Tippy} from "vue-tippy";
import {useTimeCounter} from "@/Support/Hooks";
import DangerButton from "@/Components/DangerButton.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import Counter from "@/Components/Counter.vue";

const props = defineProps<{
    canEdit: boolean;
    type: "status" | "counter";
}>();

const member = defineModel<Member>({required: true});
const {minutes, seconds} = useTimeCounter(
    () => member.value.started_at ? new Date(member.value.started_at) : null,
    () => member.value.ended_at ? new Date(member.value.ended_at) : null
);

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

async function resetTime() {
    // store the old status to revert if the request fails
    const oldStartedAt = member.value.started_at;
    const oldEndedAt = member.value.ended_at;

    // update the status in the frontend
    member.value.started_at = null;
    member.value.ended_at = null;

    try {
        // send the request to the server
        await axios.post(route('member.time.reset', {member: member.value.id}));
    } catch (e) {
        // revert the status if the request fails
        member.value.started_at = oldStartedAt;
        member.value.ended_at = oldEndedAt;
        toast.error(trans('app.error'));
    }
}

async function startTime() {
    // store the old status to revert if the request fails
    const oldValue = member.value.started_at;

    // update the status in the frontend
    member.value.started_at = new Date().toISOString();

    try {
        // send the request to the server
        await axios.post(route('member.time.start', {member: member.value.id}));
    } catch (e) {
        // revert the status if the request fails
        member.value.started_at = oldValue;
        toast.error(trans('app.error'));
    }
}

async function stopTime() {
    // store the old status to revert if the request fails
    const oldEndedAt = member.value.ended_at;
    const oldStatus = member.value.status;

    // update the status in the frontend
    member.value.ended_at = new Date().toISOString();
    member.value.status = true;

    try {
        // send the request to the server
        await axios.post(route('member.time.end', {member: member.value.id}));
    } catch (e) {
        // revert the status if the request fails
        member.value.ended_at = oldEndedAt;
        member.value.status = oldStatus;
        toast.error(trans('app.error'));
    }
}

async function resetCount() {
    // store the old status to revert if the request fails
    const oldCount = member.value.count;

    // update the status in the frontend
    member.value.count = 0;

    try {
        // send the request to the server
        await axios.post(route('member.count.reset', {member: member.value.id}));
    } catch (e) {
        // revert the status if the request fails
        member.value.count = oldCount;
        toast.error(trans('app.error'));
    }
}

async function incrementCount() {
    // store the old status to revert if the request fails
    const oldCount = member.value.count;

    // update the status in the frontend
    member.value.count++;

    try {
        // send the request to the server
        await axios.post(route('member.count.increment', {member: member.value.id}));
    } catch (e) {
        // revert the status if the request fails
        member.value.count = oldCount;
        toast.error(trans('app.error'));
    }
}

async function decrementCount() {
    // store the old status to revert if the request fails
    const oldCount = member.value.count;

    // update the status in the frontend
    member.value.count--;

    try {
        // send the request to the server
        await axios.post(route('member.count.decrement', {member: member.value.id}));
    } catch (e) {
        // revert the status if the request fails
        member.value.count = oldCount;
        toast.error(trans('app.error'));
    }
}

</script>

<template>
    <div>
        <div
            :class="{'!opacity-60':member.status || member.offline}"
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

            <div class="flex gap-1" v-if="canEdit && !member.offline">
                <DangerButton class="!px-1" @click="resetTime">
                    <font-awesome-icon icon="fa-solid fa-rotate-left" fixed-width/>
                </DangerButton>
                <SuccessButton class="!px-1" @click="startTime" :disabled="member.started_at!==null">
                    <font-awesome-icon icon="fa-solid fa-play" fixed-width/>
                </SuccessButton>
                <DangerButton class="!px-1" @click="stopTime"
                              :disabled="(member.started_at===null && member.ended_at===null) || (member.started_at!==null && member.ended_at!==null)">
                    <font-awesome-icon icon="fa-solid fa-stop" fixed-width/>
                </DangerButton>
            </div>

            <div class="font-mono text-xl"
                 v-show="!member.offline && (!(minutes==='00' && seconds==='00') || canEdit)">
                {{ minutes }}:{{ seconds }}
            </div>

            <Checkbox class="size-8" :checked="member.status" :disabled="!canEdit" v-if="!member.offline && type==='status'"
                      @change="(e: InputEvent) => updateStatus((e.target as HTMLInputElement).checked)"/>

            <Counter v-if="!member.offline && type==='counter'"
                     @reset="resetCount"
                     @decrement="decrementCount"
                     @increment="incrementCount"
                     v-model="member.count"
                     :canEdit="canEdit"/>

            <span v-if="member.offline" class="uppercase text-red-600 font-bold">
            {{ trans('app.member.status.offline') }}
        </span>
        </div>
    </div>
</template>

<style scoped lang="scss">

</style>
