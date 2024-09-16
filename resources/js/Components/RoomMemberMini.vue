<script setup lang="ts">
import {Member} from "@/types";
import Checkbox from "@/Components/Checkbox.vue";
import {trans} from "laravel-translator";
import {useTimeCounter} from "@/Support/Hooks";
import Counter from "@/Components/Counter.vue";
import Avatar from "primevue/avatar";
import {computed} from "vue";

withDefaults(defineProps<{
    canEdit?: boolean;
    advancedMode?: boolean;
    type: "status" | "counter";
    isOnline?: boolean;
    showTime?: boolean;
}>(), {
    canEdit: false,
    advancedMode: false,
    isOnline: false,
    showTime: false,
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
const isTalking = computed(() => member.value.started_at!==null && member.value.ended_at===null);
const hasOpacity = computed(() => !isDefault.value || member.value.status);

</script>

<template>
    <div class="rounded-md">
        <div :class="{'!opacity-40':hasOpacity}"
             class="flex flex-col gap-1 w-full bg-slate-800 p-1">
            <div class="flex items-center gap-1">
                <!-- Avatar -->
                <div class="inline-flex relative">
                    <Avatar icon="pi pi-user" v-if="!member.user?.avatar" class="!bg-gray-900" shape="circle"/>

                    <Avatar v-if="member.user?.avatar" :image="member.user?.avatar" shape="circle"/>

                    <div v-if="isOnline" class="absolute bottom-[-3px] right-[-3px] inline-block size-3 bg-green-500 rounded-full border-2 border-gray-800"></div>
                </div>

                <!-- Name -->
                <div class="flex-1">
                    <span class="text-xl text-white">
                        {{ member.name }}
                    </span>
                </div>

                <!-- Time Display -->
                <div class="font-mono text-xl text-gray-300"
                     v-show="isDefault && (!(minutes==='00' && seconds==='00') || (showTime && canEdit && advancedMode))">
                    {{ minutes }}:{{ seconds }}
                </div>

                <!-- Status -->
                <Checkbox class="size-7"
                          :checked="member.status"
                          :disabled="true"
                          :loadingWhenUnchecked="isTalking"
                          v-if="isDefault && type==='status'"/>

                <!-- Counter -->
                <Counter v-if="isDefault && type==='counter'" v-model="member.count"/>

                <!-- Offline Status -->
                <span v-if="isOffline" class="uppercase text-red-600 font-bold text-lg">
                {{ trans('app.member.status.offline.title') }}
            </span>

                <!-- Guest Status -->
                <span v-if="isGuest" class="uppercase text-green-600 font-bold text-lg">
                {{ trans('app.member.status.guest.title') }}
            </span>

                <!-- Pending Status -->
                <span v-if="isPending" class="uppercase text-yellow-600 font-bold text-lg">
                {{ trans('app.member.status.pending.title') }}
            </span>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
</style>
