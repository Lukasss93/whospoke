<script setup lang="ts">
import {Head} from '@inertiajs/vue3';
import {Member, MemberRole, Room, User} from "@/types";
import {computed, onMounted, onUnmounted, ref} from "vue";
import HeaderMini from "@/Components/HeaderMini.vue";
import {mapRange} from "@/Support/Helpers";
import ProgressBar from "primevue/progressbar";
import RoomMemberMini from "@/Components/RoomMemberMini.vue";
import {trans} from "laravel-translator";
import WidgetMini from "@/Components/WidgetMini.vue";
import Stopwatch from "@/Components/Stopwatch.vue";

const props = defineProps<{
    baseRoom: Room;
    baseIsMyRoom: boolean;
    baseUserRole: MemberRole;
    roomUrl: string;
}>();

const room = ref(props.baseRoom);
const onlineUsers = ref<User[]>([]);

const membersTotal = computed(() => room.value.members.filter(x => x.type === 'default').length);
const membersSpoke = computed(() => room.value.members.filter(x => x.status && x.type === 'default').length);
const membersSpokePercentual = computed(() => mapRange(membersSpoke.value, 0, membersTotal.value, 0, 100));
const membersToRender = computed(() => {
    return room.value.members.sort((a: Member, b: Member) => {
        if (a.type === 'default' && b.type !== 'default') return -1;
        if (a.type !== 'default' && b.type === 'default') return 1;
        if (a.status && !b.status) return 1;
        if (!a.status && b.status) return -1;
        return a.name.localeCompare(b.name);
    });
});

onMounted(() => {
    window.Echo
        .channel(`room.${room.value.id}`)
        .listen('RoomChangedEvent', (data: { room: Room }) => {
            room.value = data.room;
        });

    window.Echo
        .join(`room.${room.value.id}.online`)
        .here((users: User[]) => onlineUsers.value = users)
        .joining((user: User) => onlineUsers.value.push(user))
        .leaving((user: User) => onlineUsers.value = onlineUsers.value.filter(x => x.id !== user.id));
});

onUnmounted(() => {
    window.Echo.leave(`room.${room.value.id}`);
    window.Echo.leave(`room.${room.value.id}.online`);
});
</script>

<template>
    <Head :title="room.code"/>

    <div class="relative min-h-screen flex flex-col items-center">
        <!-- HEADER -->
        <HeaderMini :title="room.title" class="my-2"/>

        <main class="w-full">

            <div class="grid grid-cols-2 gap-1 mx-1">
                <!-- TIME -->
                <WidgetMini :title="trans('app.widget.time')">
                    <Stopwatch
                        class="!text-white !text-base !font-normal !font-sans"
                        :started="room.started_at ? new Date(room.started_at) : null"
                        :ended="room.ended_at ? new Date(room.ended_at) : null"
                    />
                </WidgetMini>

                <!-- WHO SPOKE -->
                <WidgetMini :title="trans('app.widget.counter')">
                    <div class="text-white text-base font-normal">
                        {{ membersSpoke }} / {{ membersTotal }}
                    </div>
                </WidgetMini>
            </div>

            <!-- PROGRESS BAR -->
            <ProgressBar :value="membersSpokePercentual" v-if="room.type==='status'"
                         class="my-1 !h-1 !rounded-none !bg-surface-800"
                         :showValue="false"/>

            <!-- MEMBERS -->
            <div class="flex flex-col gap-0.5">
                <RoomMemberMini v-model="room.members[i]"
                                v-for="(member, i) in membersToRender"
                                :key="member.id"
                                :showTime="room.type==='status'"
                                :type="room.type"
                                :isOnline="onlineUsers.some(x => x.id === room.members[i].user_id)"/>
            </div>


        </main>
    </div>
</template>

<style lang="scss">
body {
    @apply bg-slate-900 text-white;
    @apply overflow-x-hidden;
}
</style>
