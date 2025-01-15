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
import {computed, ref, watch} from "vue";
import SelectButton from 'primevue/selectbutton';
import ButtonInfo from "@/Components/ButtonInfo.vue";

const props = defineProps<{
    canEdit: boolean;
    advancedMode: boolean;
    type: "status" | "counter";
    isOnline: boolean;
    showTime: boolean;
}>();

const member = defineModel<Member>({required: true});
const {minutes, seconds} = useTimeCounter(
    () => member.value.started_at ? new Date(member.value.started_at) : null,
    () => member.value.ended_at ? new Date(member.value.ended_at) : null
);

const isDefault = computed(() => member.value.type === 'default');
const isOffline = computed(() => member.value.type === 'offline');
const isGuest = computed(() => member.value.type === 'guest');
const isPending = computed(() => member.value.type === 'pending');
const hasOpacity = computed(() => !isDefault.value || member.value.status);
const allowedTypes = ref([
    {value: 'default', icon: 'pi pi-eye', tooltip: trans('app.member.status.default.set')},
    {value: 'offline', icon: 'pi pi-eye-slash', tooltip: trans('app.member.status.offline.set')},
    {value: 'guest', icon: 'pi pi-comment', tooltip: trans('app.member.status.guest.set')},
    {value: 'pending', icon: 'pi pi-pause-circle', tooltip: trans('app.member.status.pending.set')},
]);

const isTalking = computed(() => member.value.started_at!==null && member.value.ended_at===null);
const hasTalked = computed(() => member.value.started_at!==null && member.value.ended_at!==null);

const emit = defineEmits(['avatarClick', 'timeStarted', 'statusChange']);

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
    } finally {
        emit('timeStarted', member.value);
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

watch(() => member.value.status, (status) => emit('statusChange', status));

</script>

<template>
    <div class="rounded-md flex flex-row gap-1" :class="{'loading':isTalking}">

        <div v-tippy="member.profession?.name"
             :style="{'background-color':member.profession?.color || '#000000'}"
             :class="{'!opacity-40':hasOpacity}"
             class="text-white px-2 rounded min-w-10 text-center font-bold text-sm items-center justify-center inline-flex">
            {{ member.profession?.abbreviation || '-'}}
        </div>

        <div :class="{'!opacity-60':hasOpacity}"
             class="flex flex-col gap-1 w-full bg-surface-300 dark:bg-surface-800 border border-gray-400 dark:border-gray-700 p-1 rounded-md">
            <div class="flex items-center gap-1">

                <!-- Type Selector -->
                <SelectButton v-if="canEdit && advancedMode"
                              class="hidden sm:inline-flex"
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

                <!-- Avatar -->
                <div class="inline-flex relative">
                    <Avatar :image="member.user?.avatar || undefined"
                            :label="member.user ? undefined : member.initials"
                            :class="{'cursor-pointer':canEdit, 'text-white': true}"
                            :style="{'background-color':member.color}"
                            @click="avatarClick"
                            shape="circle"/>

                    <div v-if="isOnline" class="absolute bottom-[-3px] right-[-3px] inline-block size-3 bg-green-500 rounded-full border-2 border-gray-300 dark:border-gray-800"></div>
                </div>

                <!-- Name -->
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

                <!-- Time Controls -->
                <div class="hidden sm:flex gap-1" v-if="showTime && canEdit && advancedMode && isDefault">
                    <ButtonInfo class="!px-1.5" @click="resetTime">
                        <font-awesome-icon icon="fa-solid fa-rotate-left" fixed-width/>
                    </ButtonInfo>
                    <SuccessButton class="!px-1.5" @click="startTime" v-if="!isTalking && !hasTalked">
                        <font-awesome-icon icon="fa-solid fa-play" fixed-width/>
                    </SuccessButton>
                    <DangerButton class="!px-1.5" @click="stopTime" v-if="isTalking || hasTalked" :disabled="hasTalked">
                        <font-awesome-icon icon="fa-solid fa-stop" fixed-width/>
                    </DangerButton>
                </div>

                <!-- Time Display -->
                <div class="font-mono text-xl"
                     v-show="isDefault && (!(minutes==='00' && seconds==='00') || (showTime && canEdit && advancedMode))">
                    {{ minutes }}:{{ seconds }}
                </div>

                <!-- Status -->
                <Checkbox class="size-8"
                          :checked="member.status"
                          :disabled="!canEdit"
                          :loadingWhenUnchecked="isTalking"
                          v-if="isDefault && type==='status'"
                          @change="checkboxChange"/>

                <!-- Counter -->
                <Counter v-if="isDefault && type==='counter'"
                         @reset="resetCount"
                         @decrement="decrementCount"
                         @increment="incrementCount"
                         v-model="member.count"
                         :canEdit="canEdit"/>

                <!-- Offline Status -->
                <span v-if="isOffline" class="uppercase text-red-600 font-bold text-2xl">
                {{ trans('app.member.status.offline.title') }}
            </span>

                <!-- Guest Status -->
                <span v-if="isGuest" class="uppercase text-green-600 font-bold text-2xl">
                {{ trans('app.member.status.guest.title') }}
            </span>

                <!-- Pending Status -->
                <span v-if="isPending" class="uppercase text-yellow-600 font-bold text-2xl">
                {{ trans('app.member.status.pending.title') }}
            </span>
            </div>
            <div v-if="canEdit" class="flex items-center gap-1 sm:hidden">
                <!-- Type Selector -->
                <SelectButton v-if="canEdit && advancedMode"
                              class="flex-1"
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

                <!-- Time Controls -->
                <div class="flex gap-1" v-if="canEdit && advancedMode && isDefault">
                    <ButtonInfo class="!px-1.5" @click="resetTime">
                        <font-awesome-icon icon="fa-solid fa-rotate-left" fixed-width/>
                    </ButtonInfo>
                    <SuccessButton class="!px-1.5" @click="startTime" v-if="!isTalking && !hasTalked">
                        <font-awesome-icon icon="fa-solid fa-play" fixed-width/>
                    </SuccessButton>
                    <DangerButton class="!px-1.5" @click="stopTime" v-if="isTalking || hasTalked" :disabled="hasTalked">
                        <font-awesome-icon icon="fa-solid fa-stop" fixed-width/>
                    </DangerButton>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.loading {
    --loading-color: rgba(52, 211, 153, 0.6);
    --loading-gap: -1px;
    --loading-size: 1px;
    --loading-speed: 10s;
    --loading-radius: 7px;

    &::before,
    &::after {
        content: "";
        position: absolute;
        top: var(--loading-gap);
        left: var(--loading-gap);
        right: var(--loading-gap);
        bottom: var(--loading-gap);
        border: var(--loading-size) solid var(--loading-color);
        transition: all .5s;
        animation: clippath var(--loading-speed) infinite linear;
        border-radius: var(--loading-radius);
        pointer-events: none;
    }

    &::after {
        animation: clippath var(--loading-speed) infinite calc(var(--loading-speed)/-2) linear;
    }
}

@keyframes clippath {
    0%,
    100% {
        clip-path: inset(0 0 98% 0);
    }

    25% {
        clip-path: inset(0 98% 0 0);
    }
    50% {
        clip-path: inset(98% 0 0 0);
    }
    75% {
        clip-path: inset(0 0 0 98%);
    }
}
</style>
