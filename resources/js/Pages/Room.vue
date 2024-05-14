<script setup lang="ts">
import {Head} from '@inertiajs/vue3';
import {useClipboard} from '@vueuse/core'
import {onMounted, ref, watch} from "vue";
import {Member, Room} from "@/types";
import Checkbox from "@/Components/Checkbox.vue";
import Header from "@/Components/Header.vue";
import Footer from "@/Components/Footer.vue";
import BackgroundPattern from "@/Components/BackgroundPattern.vue";
import {toast} from "vue3-toastify";
import {Tippy} from 'vue-tippy';
import axios from "axios";
import DangerButton from "@/Components/DangerButton.vue";

const props = defineProps<{
    room: Room;
    isMyRoom: boolean;
    roomUrl: string;
}>();

const source = ref(props.roomUrl);
const {text, copy, copied, isSupported} = useClipboard({source});

watch(copied, () => {
    if (copied.value) {
        toast.success('Link copiato negli appunti!');
    }
});

async function updateMemberStatus(memberIndex: number, status: boolean) {
    // store the old status to revert if the request fails
    const oldStatus = props.room.members[memberIndex].status;

    // update the status in the frontend
    props.room.members[memberIndex].status = status;

    try {
        // send the request to the server
        await axios.post(route('room.member.update', {room: props.room.code}), {
            member: memberIndex,
            status: status,
        });
    } catch (e) {
        // revert the status if the request fails
        props.room.members[memberIndex].status = oldStatus;
        toast.error('Errore durante l\'aggiornamento dello stato del membro');
    }
}

async function resetMembersStatus() {
    try {
        await axios.delete(route('room.members.reset', {room: props.room.code}));
        props.room.members.forEach(member => member.status = false);
        toast.success('Stato dei membri resettato con successo');
    } catch (e) {
        toast.error('Errore durante il reset dello stato dei membri');
    }
}

onMounted(() => {
    window.Echo
        .channel(`room.${props.room.id}`)
        .listen('MemberStatusChangedEvent', (data: { members: Member[] }) => {
            props.room.members = data.members;
        });
})

</script>

<template>
    <Head :title="room.code"/>
    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <div
            class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">

            <BackgroundPattern/>

            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <Header/>

                <main>
                    <div class="flex flex-col items-center gap-1 text-center mb-4">
                        <p class="text-xl">
                            Benvenuto! Questa è la sessione
                            <tippy content="Clicca per copiare l'url">
                                <span class="font-bold text-blue-500 cursor-pointer underline decoration-dotted"
                                      @click="copy(source)">
                                    {{ room.code }}
                                </span>
                            </tippy>
                            .
                            <br/>
                            Qui puoi vedere lo stato dei membri che hanno parlato.<br/>
                        </p>
                        <p class="text-sm">
                            I dati vengono aggiornati in
                            <span class="font-bold text-green-500 animate-pulse">tempo reale</span> dal proprietario
                            della sessione.
                        </p>
                        <p class="text-sm text-green-600" v-if="isMyRoom">
                            Come proprietario della stanza, puoi modificare lo stato dei membri.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-2 sm:mx-10 md:mx-32 lg:mx-52 mb-4">
                        <div class="flex items-center gap-2 w-full bg-gray-300 dark:bg-gray-800 p-1 rounded"
                             v-for="(member, i) in room.members" :key="i">
                            <div class="flex-1 text-2xl text-black dark:text-white">
                                {{ member.name }}
                            </div>
                            <div>
                                <Checkbox class="size-8"
                                          :checked="member.status"
                                          :disabled="!isMyRoom"
                                          @change="(e: InputEvent) => updateMemberStatus(i, (e.target as HTMLInputElement).checked)"/>
                            </div>

                        </div>
                    </div>

                    <div class="flex flex-col items-center gap-1 text-center" v-if="isMyRoom">
                        <DangerButton @click="resetMembersStatus">
                            Resetta lo stato di tutti i membri
                        </DangerButton>
                    </div>
                </main>

                <Footer/>
            </div>
        </div>
    </div>
</template>
