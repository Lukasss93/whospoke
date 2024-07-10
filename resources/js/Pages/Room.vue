<script setup lang="ts">
import {Head, usePage} from '@inertiajs/vue3';
import {useClipboard, useStorage} from '@vueuse/core'
import {computed, onMounted, onUnmounted, ref, toRaw, watch} from "vue";
import {Member, MemberRole, Room, User} from "@/types";
import Header from "@/Components/Header.vue";
import Footer from "@/Components/Footer.vue";
import BackgroundPattern from "@/Components/BackgroundPattern.vue";
import {toast} from "vue3-toastify";
import {Tippy} from 'vue-tippy';
import axios from "axios";
import {trans, trans_choice} from "laravel-translator";
import Stopwatch from "@/Components/Stopwatch.vue";
import RoomMember from "@/Components/RoomMember.vue";
import Avatar from 'primevue/avatar';
import {chunk, mapRange} from "@/Support/Helpers";
import ButtonLogout from "@/Components/ButtonLogout.vue";
import ButtonLogin from "@/Components/ButtonLogin.vue";
import MemberUserLink from "@/Modals/MemberUserLink.vue";
import Widget from "@/Components/Widget.vue";
import Dropdown from 'primevue/dropdown';
import {DateTime} from "luxon";
import Button from 'primevue/button';
import ToggleButton from 'primevue/togglebutton';
import ProgressBar from 'primevue/progressbar';
import Tag from 'primevue/tag';
import { useWindowFocus } from '@vueuse/core';
import memoize from 'memoize';

const focused = useWindowFocus();
const getServerVersion = memoize(async()=>{
    const response = await axios.get(route('version'));
    return response.data.version;
},{maxAge:1000*60*60});

const page = usePage();
const isLogged = computed(() => page.props.auth.user !== null);

const props = defineProps<{
    baseRoom: Room;
    baseIsMyRoom: boolean;
    baseUserRole: MemberRole;
    roomUrl: string;
}>();

const room = ref(props.baseRoom);
const isMyRoom = ref(props.baseIsMyRoom);
const userRole = ref(props.baseUserRole);

const advancedMode = useStorage('advancedMode', true);
const onlineUsers = ref<User[]>([]);
const source = ref(props.roomUrl);
const {text, copy, copied, isSupported} = useClipboard({source});

const membersTotal = computed(() => room.value.members.filter(x => x.type==='default' || x.type==='pending').length);
const membersSpoke = computed(() => room.value.members.filter(x => x.status && x.type==='default').length);
const membersSpokePercentual = computed(() => mapRange(membersSpoke.value, 0, membersTotal.value, 0, 100));
const isSessionCompleted = computed(() => membersSpoke.value === membersTotal.value);
const nextAvailableMember = ref('-');

const userRoleLabel = computed(() => {
    if(userRole.value === 'owner') {
        return trans('app.room.owner');
    }
    if(userRole.value === 'editor'){
        return trans('app.room.editor');
    }
    return '-';
});

const sortTypes = [
    {value: 'name_asc', label: trans('app.sorting.name')+' ▲'},
    {value: 'name_desc', label: trans('app.sorting.name')+' ▼'},
    {value: 'time_asc', label: trans('app.sorting.time')+' ▲'},
    {value: 'time_desc', label: trans('app.sorting.time')+' ▼'},
    {value: 'speech_asc', label: trans('app.sorting.speech')+' ▲'},
    {value: 'speech_desc', label: trans('app.sorting.speech')+' ▼'},
];
const sortType = useStorage('sortType', sortTypes[0]);
const membersToRender = computed(() => {
    switch (sortType.value.value) {
        case 'name_asc':
            return room.value.members.sort((a, b) => a.name.localeCompare(b.name));
        case 'name_desc':
            return room.value.members.sort((a, b) => b.name.localeCompare(a.name));
        case 'time_asc':
            return room.value.members.sort((a:Member, b:Member) => {
                if(!a.ended_at || !b.ended_at) {
                    return a.ended_at ? -1 : 1;
                }

                const startA = DateTime.fromJSDate(!a.started_at ? new Date(a.ended_at) : new Date(a.started_at));
                const endA = DateTime.fromJSDate(new Date(a.ended_at));
                const startB = DateTime.fromJSDate(!b.started_at ? new Date(b.ended_at) : new Date(b.started_at));
                const endB = DateTime.fromJSDate(new Date(b.ended_at));

                const diffA = endA.diff(startA, ['seconds']).seconds;
                const diffB = endB.diff(startB, ['seconds']).seconds;

                if(diffA===diffB) {
                    return 0;
                }

                return diffA > diffB ? 1 : -1;
            });
        case 'time_desc':
            return room.value.members.sort((a:Member, b:Member) => {
                if(!a.ended_at || !b.ended_at) {
                    return a.ended_at ? -1 : 1;
                }

                const startA = DateTime.fromJSDate(!a.started_at ? new Date(a.ended_at) : new Date(a.started_at));
                const endA = DateTime.fromJSDate(new Date(a.ended_at));
                const startB = DateTime.fromJSDate(!b.started_at ? new Date(b.ended_at) : new Date(b.started_at));
                const endB = DateTime.fromJSDate(new Date(b.ended_at));

                const diffA = endA.diff(startA, ['seconds']).seconds;
                const diffB = endB.diff(startB, ['seconds']).seconds;

                if(diffA===diffB) {
                    return 0;
                }

                return diffA > diffB ? -1 : 1;
            });
        case 'speech_asc':
            return room.value.members.sort((a, b) => {
                if(!a.ended_at && b.ended_at) {
                    return 1;
                }

                const endA = a.ended_at ? new Date(a.ended_at).getTime() : 0;
                const endB = b.ended_at ? new Date(b.ended_at).getTime() : 0;

                if(endA===endB){
                    return 0;
                }

                return endA > endB ? 1 : -1;
            });
        case 'speech_desc':
            return room.value.members.sort((a, b) => {
                if(!a.ended_at && b.ended_at) {
                    return 1;
                }

                const endA = a.ended_at ? new Date(a.ended_at).getTime() : 0;
                const endB = b.ended_at ? new Date(b.ended_at).getTime() : 0;

                if(endA===endB){
                    return 0;
                }

                return endA > endB ? -1 : 1;
            });
        default:
            return room.value.members;
    }
});

watch(()=>room.value.members.filter(x => !x.status && x.type==='default' && !(x.started_at!==null && x.ended_at===null)), (newValue, oldValue) => {
    if(newValue.length === 0) {
        nextAvailableMember.value = '-';
        return;
    }

    const newHash = newValue.map(x => x.id).sort().join(',');
    const oldHash = oldValue?.map(x => x.id)?.sort()?.join(',');

    if(newHash === oldHash) {
        return;
    }

    nextAvailableMember.value = newValue[newValue.length * Math.random() | 0].name;
}, {immediate: true});

watch(copied, () => {
    if (copied.value) {
        toast.success(trans('app.room.link.copied'));
    }
});

const memberUserLinkMember = ref<Member | null>(null);
const memberUserLinkShow = computed(() => memberUserLinkMember.value !== null);

function openMemberUserLink(member: Member) {
    memberUserLinkMember.value = structuredClone(toRaw(member));
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

async function setMembersOnline() {
    // store the old status to revert if the request fails
    const oldMembers = room.value.members;

    // update the status in the frontend
    room.value.members.forEach(function (member) {
        if(member.type === 'offline' || member.type === 'guest'){
            return;
        }

        return member.type = 'default';
    });

    try {
        // send the request to the server
        await axios.patch(route('room.members.online', {room: room.value.code}));
    } catch (e) {
        // revert the status if the request fails
        room.value.members = oldMembers;
        toast.error(trans('app.error'));
    }
}

async function setMembersPending() {
    // store the old status to revert if the request fails
    const oldMembers = room.value.members;

    // update the status in the frontend
    room.value.members.forEach(function (member) {
        if(member.type === 'offline' || member.type === 'guest'){
            return;
        }

        return member.type = 'pending';
    });

    try {
        // send the request to the server
        await axios.patch(route('room.members.pending', {room: room.value.code}));
    } catch (e) {
        // revert the status if the request fails
        room.value.members = oldMembers;
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

watch(focused, async (value) => {
    if(value){
        const serverVersion = await getServerVersion();

        if(page.props.app.version !== serverVersion){
            window.location.reload();
        }
    }
});

onMounted(() => {
    window.Echo
        .channel(`room.${room.value.id}`)
        .listen('RoomChangedEvent', (data: { room: Room }) => {
            room.value = data.room;
        });

    if(page.props.auth.user !== null){
        window.Echo
            .channel(`room.${room.value.id}.editor.${page.props.auth.user.id}`)
            .listen('EditorChangedEvent', (data: { room: Room, isMyRoom: boolean, userRole: MemberRole }) => {
                room.value = data.room;
                isMyRoom.value = data.isMyRoom;
                userRole.value = data.userRole;
            });
    }

    window.Echo
        .join(`room.${room.value.id}.online`)
        .here((users: User[]) => {
            onlineUsers.value = users;
        })
        .joining((user: User) => {
            onlineUsers.value.push(user);
        })
        .leaving((user: User) => {
            onlineUsers.value = onlineUsers.value.filter(x => x.id !== user.id);
        });
});

onUnmounted(() => {
    window.Echo.leave(`room.${room.value.id}`);
    window.Echo.leave(`room.${room.value.id}.online`);
});

</script>

<template>
    <Head :title="room.code"/>

    <Teleport to="body">
        <!-- REALTIME LABEL -->
        <span class="live-badge absolute top-2 left-2">
            <font-awesome-icon icon="fa-solid fa-tower-broadcast" class="mr-2"/> LIVE
        </span>
    </Teleport>

    <MemberUserLink v-model:show="memberUserLinkShow"
                    v-model:member="memberUserLinkMember"
                    @close="memberUserLinkMember = null"
                    @save="memberUserLinkMember = null;"/>

    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <div
            class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">

            <BackgroundPattern/>

            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <Header :title="room.title"/>

                <main class="container mx-auto">

                    <!-- SESSION NAME (WHEN ROOM TYPE = COUNTER) -->
                    <div class="text-center text-xl text-gray-600 dark:text-gray-400" v-if="room.type==='counter'">
                        {{ trans('app.room.welcome') }}
                        <tippy :content="trans('app.room.link.copy')">
                                <span class="font-bold text-blue-500 cursor-pointer underline decoration-dotted"
                                      @click="copy(source)">
                                    {{ room.code }}
                                </span>
                        </tippy>
                    </div>

                    <!-- ADMIN TOOLBAR -->
                    <div v-if="isMyRoom"
                         class="my-2 grid lg:grid-cols-3 gap-2 *:p-1 *:rounded-md *:bg-surface-300 *:dark:bg-surface-800 *:border *:border-gray-400 *:dark:border-gray-700">
                        <div class="flex items-center justify-center text-green-600 text-sm font-bold uppercase">
                            {{ userRoleLabel }}
                        </div>
                        <div class="grid grid-cols-3 gap-1">
                            <Button severity="info" size="small" outlined class="font-bold" @click="reset">
                                <font-awesome-icon fixed-width icon="fa-solid fa-rotate-left"/> {{trans('app.time.reset')}}
                            </Button>
                            <Button severity="success" size="small" outlined class="font-bold" @click="startRoom" :disabled="room.started_at!==null">
                                <font-awesome-icon fixed-width icon="fa-solid fa-play"/> {{trans('app.time.play')}}
                            </Button>
                            <Button severity="danger" size="small" outlined class="font-bold" @click="stopRoom" :disabled="(room.started_at===null && room.ended_at===null) || (room.started_at!==null && room.ended_at!==null)">
                                <font-awesome-icon fixed-width icon="fa-solid fa-stop"/> {{trans('app.time.stop')}}
                            </Button>
                        </div>
                        <div class="grid grid-cols-3 gap-1">
                            <Button :label="trans('app.member.status.default.all')"
                                    v-tooltip.top="trans('app.member.status.except')"
                                    class="text-xs !p-1 text-balance"
                                    @click="setMembersOnline"
                                    severity="secondary" outlined />
                            <Button :label="trans('app.member.status.pending.all')"
                                    v-tooltip.top="trans('app.member.status.except')"
                                    class="text-xs !p-1 text-balance"
                                    @click="setMembersPending"
                                    severity="secondary" outlined />
                            <ToggleButton v-model="advancedMode"
                                          class="text-xs"
                                          :pt:box="({props}) => ({class: [{'!text-white before:!bg-blue-600 dark:before:!bg-blue-600': props.modelValue}]})"
                                          pt:box:class="!p-1 text-balance"
                                          :onLabel="trans('app.show_advanced')"
                                          :offLabel="trans('app.show_advanced')" />
                        </div>
                    </div>

                    <!-- ROOM TOOLBAR (WHEN ROOM TYPE = STATUS) -->
                    <div v-if="room.type==='status'"
                         class="grid grid-cols-1 md:grid-cols-6 lg:grid-cols-5 items-center gap-2 mb-2">
                        <div class="h-full md:col-span-2 lg:col-auto">
                            <Widget :title="trans('app.widget.session')">
                                <tippy :content="trans('app.room.link.copy')">
                                <span class="font-bold text-blue-500 cursor-pointer underline underline-offset-2 decoration-dotted"
                                      @click="copy(source)">
                                    {{ room.code }}
                                </span>
                                </tippy>
                            </Widget>
                        </div>
                        <div class="h-full md:col-span-2 lg:col-auto md:col-start-5 lg:col-start-2 lg:row-start-1">
                            <Widget :title="trans('app.widget.counter')">
                                <div class="text-black dark:text-white text-lg font-bold">
                                    {{ membersSpoke }} / {{ membersTotal }}
                                </div>
                            </Widget>
                        </div>
                        <div class="h-full md:col-span-2 lg:col-auto md:col-start-3 md:row-start-1 lg:col-start-3 lg:row-start-1">
                            <Widget :title="trans('app.widget.time')">
                                <Stopwatch
                                    :started="room.started_at ? new Date(room.started_at) : null"
                                    :ended="room.ended_at ? new Date(room.ended_at) : null"
                                />
                            </Widget>
                        </div>
                        <div class="h-full md:col-span-3 lg:col-auto">
                            <Widget :title="trans('app.widget.available')">
                                <div v-if="!isSessionCompleted" class="text-black dark:text-white text-lg font-bold">
                                    {{ nextAvailableMember }}
                                </div>
                                <Tag v-if="isSessionCompleted"
                                     severity="success" class="!py-0.5"
                                     :value="trans('app.room.completed')" />
                            </Widget>
                        </div>
                        <div class="h-full md:col-span-3 lg:col-auto">
                            <Widget :title="trans('app.widget.sort')">
                                <Dropdown v-model="sortType"
                                          :options="sortTypes"
                                          optionLabel="label"
                                          pt:input:class="!pl-2 !pr-0"
                                          class="text-xs w-full text-left mt-1">
                                    <template #value="slotProps">
                                        {{ sortTypes.find(x=>x.value===slotProps.value.value)?.label }}
                                    </template>
                                </Dropdown>
                            </Widget>
                        </div>
                    </div>

                    <!-- PROGRESS BAR -->
                    <ProgressBar :value="membersSpokePercentual"
                                 class="mb-2 !h-1 !rounded-sm !bg-surface-300 dark:!bg-surface-800" :showValue="false"/>

                    <!-- MEMBERS -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-2 mb-2">
                        <RoomMember v-model="room.members[i]"
                                    @avatarClick="openMemberUserLink(room.members[i])"
                                    :canEdit="isMyRoom"
                                    :advancedMode="advancedMode"
                                    :type="room.type"
                                    :isOnline="onlineUsers.some(x => x.id === room.members[i].user_id)"
                                    v-for="(member, i) in membersToRender" :key="member.id"
                                    v-motion
                                    :initial="{ opacity: 0, scale:0.8 }"
                                    :enter="{ opacity: 1, scale: 1 }"
                                    :delay="(i*60)"
                                    :duration="300"/>

                        <div v-if="room.members.length%2===1"
                             class="h-full"
                             v-motion
                             :initial="{ opacity: 0, scale:0.8 }"
                             :enter="{ opacity: 1, scale: 1 }"
                             :delay="(room.members.length*60)"
                             :duration="300">
                            <div class="hidden h-full lg:flex justify-center items-center gap-1 w-full opacity-15 font-bold text-black dark:text-white border-dashed border-2 border-black dark:border-white p-1 rounded-md">
                                ＞︿＜
                            </div>
                        </div>
                    </div>

                    <!-- ONLINE USERS -->
                    <div class="text-center" :class="{'text-red-500': onlineUsers.length===0, 'text-green-500': onlineUsers.length>0}">
                        {{ trans_choice('app.room.online', onlineUsers.length) }}
                    </div>

                    <!-- ONLINE AVATARS -->
                    <div class="flex flex-col items-center mb-2">
                        <XyzTransitionGroup
                            tag="div" class="flex" v-for="users in chunk(onlineUsers, 8)"
                            xyz="fade small duration-1" appear
                        >
                            <Avatar v-for="user in users" :key="user.id"
                                    v-tippy="user.name"
                                    :image="user.avatar || undefined"
                                    :label="user.avatar ? undefined : user.initials"
                                    class="m-0.5 text-white" :style="{'background-color':user.color}"
                                    shape="circle"/>
                        </XyzTransitionGroup>
                    </div>

                    <!-- LOGIN BUTTON -->
                    <div class="text-center">
                        <ButtonLogin v-if="!isLogged" size="small" :redirect="route(route().current() ?? '', route().params)"/>
                    </div>

                    <!-- LOGOUT BUTTON -->
                    <div class="text-center">
                        <ButtonLogout v-if="isLogged" :redirect="route(route().current() ?? '', route().params)"/>
                    </div>

                </main>

                <Footer/>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.live-badge {
    @apply inline-block px-4;
    @apply bg-red-500 text-white;
    @apply font-bold text-2xl whitespace-nowrap;
    @apply rounded animate-pulse;
}

.like-button {
    @apply select-none p-1 aspect-square;
    @apply bg-gray-300 dark:bg-gray-800;
    @apply border border-gray-400 dark:border-gray-700 rounded-full;
    @apply opacity-40 hover:opacity-100;
    @apply transition-all duration-300 ease-in-out hover:scale-110 active:scale-95;
}
</style>
