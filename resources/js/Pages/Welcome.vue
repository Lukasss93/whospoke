<script setup lang="ts">
import {Head, Link, router, useForm, usePage} from '@inertiajs/vue3';
import {computed, ref} from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {Room} from "@/types";
import Header from "@/Components/Header.vue";
import Footer from "@/Components/Footer.vue";
import BackgroundPattern from "@/Components/BackgroundPattern.vue";
import CreateRoom from "@/Modals/CreateRoom.vue";
import {trans} from "laravel-translator";
import ButtonLogout from "@/Components/ButtonLogout.vue";
import ButtonLogin from "@/Components/ButtonLogin.vue";
import {useConfirm} from "primevue/useconfirm";
import Button from 'primevue/button';
import ConfirmDialog from 'primevue/confirmdialog';
import {toast} from "vue3-toastify";

const confirm = useConfirm();
const page = usePage();
const isLogged = computed(() => page.props.auth.user !== null);

const isCreatingRoom = ref<boolean>(false);
const roomForm = useForm<{
    title: string;
    code: string;
    members: string[];
    type: string;
}>({
    title: '',
    code: '',
    members: [],
    type: 'status',
});

const storeRoom = () => {
    roomForm.clearErrors();
    roomForm.post(route('room.store'), {
        onSuccess: () => isCreatingRoom.value = false,
    });
};

defineProps<{
    rooms: Room[];
    canCreateRooms: boolean;
}>();

const confirmRoomDelete = (event, roomCode) => {
    confirm.require({
        target: event.currentTarget,
        header: trans('actions.confirm'),
        message: trans('app.room.delete.question'),
        rejectClass: 'text-white !bg-transparent hover:!bg-gray-600 !border-gray-500 hover:!border-gray-600 focus:!ring-gray-600',
        acceptClass: 'text-white !bg-red-500 hover:!bg-red-600 !border-red-500 hover:!border-red-600 focus:!ring-red-600',
        rejectLabel: trans('actions.cancel'),
        acceptLabel: trans('actions.delete'),
        accept: () => {
            router.delete(route('room.delete', roomCode), {
                onSuccess: () => toast.success(trans('app.room.delete.success')),
            });
        },
    });
};

</script>

<template>
    <ConfirmDialog></ConfirmDialog>
    <Head title="Home Page"/>
    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <div
            class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">

            <BackgroundPattern/>

            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <Header/>

                <main>

                    <div class="flex flex-col items-center gap-2 text-center">

                        <ButtonLogin v-if="!isLogged"/>

                        <p class="text-yellow-600 dark:text-yellow-500" v-if="!isLogged">
                            {{ trans('app.create.guest') }}
                        </p>

                        <p class="text-xl text-gray-600 dark:text-gray-400" v-if="isLogged">
                            {{ trans('app.welcome', {name: page.props.auth.user?.first_name}) }}
                        </p>
                        <PrimaryButton v-if="isLogged && canCreateRooms" @click="isCreatingRoom=true">
                            {{ trans('app.create.label') }}
                        </PrimaryButton>

                        <div v-if="isLogged && !canCreateRooms" class="text-sm text-red-500 text-center">
                            <p>{{ trans('app.create.limit') }}</p>
                            <p>{{ trans('app.create.recreate') }}</p>
                        </div>

                        <CreateRoom :show="isCreatingRoom"
                                    @close="isCreatingRoom=false"
                                    @save="storeRoom"
                                    v-model:title="roomForm.title"
                                    v-model:code="roomForm.code"
                                    v-model:members="roomForm.members"
                                    v-model:type="roomForm.type"
                                    v-model:errors="roomForm.errors"/>

                        <p class="text-xl mt-2 text-gray-600 dark:text-gray-400">
                            {{ trans('app.join.info') }}
                        </p>

                        <div v-if="isLogged && rooms.length>0" class="flex flex-col items-center gap-1">
                            <div class="border-b-[1px] w-64 my-2 border-gray-500"></div>
                            <p class="text-sm uppercase font-bold text-gray-600 dark:text-gray-400">
                                {{ trans('app.your_rooms')}}
                            </p>

                            <table>
                                <tbody>
                                <tr v-for="(room, i) in rooms" :key="room.id" class="*:px-2 *:py-1">
                                    <td class="text-right text-gray-600 dark:text-gray-400">
                                        #{{ i + 1 }}
                                    </td>
                                    <td class="text-left">
                                        <Link :href="route('room.show', room.code)"
                                              class="text-blue-500 hover:underline">
                                            {{ room.code }}
                                        </Link>
                                    </td>
                                    <td>
                                        <Button @click="confirmRoomDelete($event, room.code)" label="Delete"
                                                severity="danger" outlined size="small">
                                            <font-awesome-icon icon="fa-solid fa-xmark"/>
                                        </Button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="border-b-[1px] w-64 my-2 border-gray-500" v-if="isLogged"></div>

                        <ButtonLogout v-if="isLogged" :redirect="route(route().current() ?? '', route().params)"/>
                    </div>
                </main>

                <Footer/>
            </div>
        </div>
    </div>
</template>
