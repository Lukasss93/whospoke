<script setup lang="ts">
import {Head} from '@inertiajs/vue3';
import {useClipboard} from '@vueuse/core'
import {onMounted, onUnmounted, ref, watch} from "vue";
import {Member, Room} from "@/types";
import Header from "@/Components/Header.vue";
import Footer from "@/Components/Footer.vue";
import BackgroundPattern from "@/Components/BackgroundPattern.vue";
import {toast} from "vue3-toastify";
import {Tippy} from 'vue-tippy';
import axios from "axios";
import DangerButton from "@/Components/DangerButton.vue";
import Interpolator from "@/Components/Interpolator.vue";
import {trans, trans_choice} from "laravel-translator";
import Stopwatch from "@/Components/Stopwatch.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import RoomMember from "@/Components/RoomMember.vue";

const props = defineProps<{
    baseRoom: Room;
    isMyRoom: boolean;
    roomUrl: string;
}>();

const room = ref(props.baseRoom);
const onlineUsers = ref(0);
const source = ref(props.roomUrl);
const {text, copy, copied, isSupported} = useClipboard({source});

watch(copied, () => {
    if (copied.value) {
        toast.success(trans('app.room.link.copied'));
    }
});

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

async function reset() {
    // store the old status to revert if the request fails
    const oldMembers = room.value.members;
    const oldStartedAt = room.value.started_at;
    const oldEndedAt = room.value.ended_at;

    // update the status in the frontend
    room.value.members.forEach(member => member.status = false);
    room.value.started_at = null;
    room.value.ended_at = null;

    try {
        // send the request to the server
        await axios.delete(route('room.reset', {room: room.value.code}));
    } catch (e) {
        // revert the status if the request fails
        room.value.members = oldMembers;
        room.value.started_at = oldStartedAt;
        room.value.ended_at = oldEndedAt;
        toast.error(trans('app.error'));
    }
}

async function startRoom() {
    // store the old status to revert if the request fails
    const oldStatus = room.value.started_at;

    // update the status in the frontend
    room.value.started_at = new Date().toISOString();

    try {
        // send the request to the server
        await axios.post(route('room.time.start', {room: room.value.code}));
    } catch (e) {
        // revert the status if the request fails
        room.value.started_at = oldStatus;
        toast.error(trans('app.error'));
    }
}

async function stopRoom() {
    // store the old status to revert if the request fails
    const oldStatus = room.value.ended_at;

    // update the status in the frontend
    room.value.ended_at = new Date().toISOString();

    try {
        // send the request to the server
        await axios.post(route('room.time.stop', {room: room.value.code}));
    } catch (e) {
        // revert the status if the request fails
        room.value.ended_at = oldStatus;
        toast.error(trans('app.error'));
    }
}

onMounted(() => {
    window.Echo
        .channel(`room.${room.value.id}`)
        .listen('RoomChangedEvent', (data: { room: Room }) => {
            room.value = data.room;
        });

    window.Echo
        .join(`room.${room.value.id}.online`)
        .here((users: string[]) => {
            onlineUsers.value = users.length;
        })
        .joining(() => {
            onlineUsers.value++;
        })
        .leaving(() => {
            onlineUsers.value--;
        });
});

onUnmounted(() => {
    window.Echo.leave(`room.${room.value.id}`);
    window.Echo.leave(`room.${room.value.id}.online`);
});

</script>

<template>
    <Head :title="room.code"/>
    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <div
            class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">

            <BackgroundPattern/>

            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <Header :title="room.title"/>

                <main>
                    <div class="flex flex-col items-center gap-1 text-center mb-2">
                        <p class="text-xl text-gray-600 dark:text-gray-400">
                            {{ trans('app.room.welcome') }}
                            <tippy :content="trans('app.room.link.copy')">
                                <span class="font-bold text-blue-500 cursor-pointer underline decoration-dotted"
                                      @click="copy(source)">
                                    {{ room.code }}
                                </span>
                            </tippy>
                        </p>
                        <p class="text-xl text-gray-600 dark:text-gray-400">
                            {{ trans('app.room.info') }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <Interpolator :message="trans('app.room.live')">
                                <template v-slot:live>
                                    <span class="live-badge">{{ trans('app.live') }}</span>
                                </template>
                            </Interpolator>
                        </p>
                        <p class="text-sm text-green-600" v-if="isMyRoom">
                            {{ trans('app.room.owner') }}
                        </p>

                        <Stopwatch
                            class="mt-2"
                            v-if="room.started_at"
                            :started="room.started_at ? new Date(room.started_at) : null"
                            :ended="room.ended_at ? new Date(room.ended_at) : null"
                        />
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-2 sm:mx-10 lg:mx-32 mb-2">
                        <RoomMember v-model="room.members[i]" :canEdit="isMyRoom" :type="room.type"
                                    v-for="(member, i) in room.members" :key="member.id"/>
                    </div>

                    <div class="flex flex-col items-center gap-2 text-center">
                        <p :class="{'text-red-500': onlineUsers===0, 'text-green-500': onlineUsers>0}">
                            {{ trans_choice('app.room.online', onlineUsers) }}
                        </p>

                        <div class="flex gap-2" v-if="isMyRoom">
                            <DangerButton @click="reset">
                                <font-awesome-icon icon="fa-solid fa-rotate-left"/>
                            </DangerButton>
                            <SuccessButton @click="startRoom" :disabled="room.started_at!==null">
                                <font-awesome-icon icon="fa-solid fa-play"/>
                            </SuccessButton>
                            <DangerButton @click="stopRoom"
                                          :disabled="(room.started_at===null && room.ended_at===null) || (room.started_at!==null && room.ended_at!==null)">
                                <font-awesome-icon icon="fa-solid fa-stop"/>
                            </DangerButton>
                        </div>
                    </div>
                </main>

                <Footer/>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.live-badge {
    @apply font-bold bg-red-500 text-white px-1 rounded whitespace-nowrap animate-pulse;
}
</style>
