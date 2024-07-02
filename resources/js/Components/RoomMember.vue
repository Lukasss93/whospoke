<script setup lang="ts">
import {Member, MemberType} from "@/types";
import Checkbox from "@/Components/Checkbox.vue";
import axios from "axios";
import {toast} from "vue3-toastify";
import {trans} from "laravel-translator";
import {Tippy} from "vue-tippy";
import {useTimeCounter} from "@/Support/Hooks";
import DangerButton from "@/Components/DangerButton.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import Counter from "@/Components/Counter.vue";
import Avatar from "primevue/avatar";
import {useSound} from '@vueuse/sound';
import bellSound from "@sound/bell.mp3";
import {computed, ref, watch} from "vue";
import SelectButton from 'primevue/selectbutton';
import AutoComplete from "primevue/autocomplete";

const props = defineProps<{
    canEdit: boolean;
    type: "status" | "counter";
    isOnline: boolean;
}>();

const checkedSound = useSound(bellSound, {
    volume: 0.01
});

const member = defineModel<Member>({required: true});
const {minutes, seconds} = useTimeCounter(
    () => member.value.started_at ? new Date(member.value.started_at) : null,
    () => member.value.ended_at ? new Date(member.value.ended_at) : null
);

const isDefault = computed(() => member.value.type === 'default');
const isOffline = computed(() => member.value.type === 'offline');
const isGuest = computed(() => member.value.type === 'guest');
const isPending = computed(() => member.value.type === 'pending');
const allowedTypes = ref([
    {value: 'default', icon: 'pi pi-eye', tooltip: trans('app.member.status.default.set')},
    {value: 'offline', icon: 'pi pi-eye-slash', tooltip: trans('app.member.status.offline.set')},
    {value: 'guest', icon: 'pi pi-comment', tooltip: trans('app.member.status.guest.set')},
    {value: 'pending', icon: 'pi pi-pause-circle', tooltip: trans('app.member.status.pending.set')},
]);

const emit = defineEmits(['avatarClick']);

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

async function updateType(type: MemberType|null) {
    if(type === null) {
        return;
    }

    // store the old status to revert if the request fails
    const oldType = member.value.type;

    // update the status in the frontend
    member.value.type = type;

    try {
        // send the request to the server
        await axios.post(route('member.type.update', {member: member.value.id}), {
            type: type,
        });
    } catch (e) {
        // revert the status if the request fails
        member.value.type = oldType;
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

function checkboxChange(e: InputEvent) {
    let status: boolean = (e.target as HTMLInputElement).checked;
    updateStatus(status);
}

function avatarClick() {
    if (!props.canEdit) {
        return;
    }

    emit('avatarClick', member.value);
}

watch(() => member.value.status, (status) => {
    if (status) {
        checkedSound.play();
    }
});

</script>

<template>
    <div>
        <div
            :class="{'!opacity-60':member.status || !isDefault}"
            class="flex items-center gap-1 w-full bg-surface-300 dark:bg-surface-800 border border-gray-400 dark:border-gray-700 p-1 rounded-md">

            <SelectButton v-if="canEdit"
                          :model-value="member.type"
                          @update:model-value="updateType"
                          :options="allowedTypes"
                          optionLabel="value"
                          optionValue="value"
                          pt:button:class="!px-2 !text-xs"
                          dataKey="value">
                <template #option="slotProps">
                    <tippy :content="slotProps.option.tooltip">
                        <i :class="slotProps.option.icon"></i>
                    </tippy>
                </template>
            </SelectButton>

            <div class="inline-flex relative">
                <Avatar icon="pi pi-user"
                        v-if="!member.user?.avatar"
                        class="!bg-gray-400 dark:!bg-gray-900"
                        :class="{'cursor-pointer':canEdit}"
                        @click="avatarClick"
                        shape="circle"/>

                <Avatar v-if="member.user?.avatar"
                        :image="member.user?.avatar"
                        :class="{'cursor-pointer':canEdit}"
                        @click="avatarClick"
                        shape="circle"/>

                <div v-if="isOnline" class="absolute bottom-[-3px] right-[-3px] inline-block size-3 bg-green-500 rounded-full border-2 border-gray-300 dark:border-gray-800"></div>
            </div>

            <div class="flex-1">
                <tippy :content="trans('app.member.canEdit')" v-if="member.user?.canEdit">
                    <span class="text-2xl text-yellow-700 dark:text-yellow-500 cursor-help underline underline-offset-2 decoration-dotted">
                        {{ member.name }}
                    </span>
                </tippy>

                <span v-if="!member.user?.canEdit" class="text-2xl text-black dark:text-white">
                    {{ member.name }}
                </span>
            </div>

            <div class="flex gap-1" v-if="canEdit && isDefault">
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
                 v-show="isDefault && (!(minutes==='00' && seconds==='00') || canEdit)">
                {{ minutes }}:{{ seconds }}
            </div>

            <Checkbox class="size-8"
                      :checked="member.status"
                      :disabled="!canEdit"
                      :loadingWhenUnchecked="member.started_at!==null && member.ended_at===null"
                      v-if="isDefault && type==='status'"
                      @change="checkboxChange"/>

            <Counter v-if="isDefault && type==='counter'"
                     @reset="resetCount"
                     @decrement="decrementCount"
                     @increment="incrementCount"
                     v-model="member.count"
                     :canEdit="canEdit"/>

            <span v-if="isOffline" class="uppercase text-red-600 font-bold text-2xl">
                {{ trans('app.member.status.offline.title') }}
            </span>

            <span v-if="isGuest" class="uppercase text-green-600 font-bold text-2xl">
                {{ trans('app.member.status.guest.title') }}
            </span>

            <span v-if="isPending" class="uppercase text-yellow-600 font-bold text-2xl">
                {{ trans('app.member.status.pending.title') }}
            </span>
        </div>
    </div>
</template>

<style scoped lang="scss">

</style>
