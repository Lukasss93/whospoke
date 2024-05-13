<script setup lang="ts">
import {Head, usePage} from '@inertiajs/vue3';
import {useClipboard, useDark} from '@vueuse/core'
import {computed, ref} from "vue";
import {Room} from "@/types";
import Checkbox from "@/Components/Checkbox.vue";
import Header from "@/Components/Header.vue";
import Footer from "@/Components/Footer.vue";
import BackgroundPattern from "@/Components/BackgroundPattern.vue";

const props = defineProps<{
    room: Room;
    isMyRoom: boolean;
    roomUrl: string;
}>();

const isDark = useDark();
const page = usePage();
const logoColor = computed(() => isDark.value ? 'white' : 'black');
const isLogged = computed(() => page.props.auth.user !== null);
const source = ref(props.roomUrl);
const {text, copy, copied, isSupported} = useClipboard({source});

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
                            Benvenuto! Questa è la stanza
                            <abbr title="Clicca per copiare l'url"
                                  @click="copy(source)"
                                  class="font-bold text-blue-500 cursor-pointer">
                                {{ room.code }}
                            </abbr>.<br/>
                            Qui puoi vedere lo stato dei membri che hanno parlato.<br/>
                        </p>
                        <p class="text-sm">
                            Non hai bisogno di aggiornare la pagina, i dati vengono aggiornati in
                            <span class="font-bold text-orange-400 animate-pulse">tempo reale</span>.
                        </p>
                        <p class="text-sm text-green-600" v-if="isMyRoom">
                            Come proprietario della stanza, puoi modificare lo stato dei membri.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-2 sm:mx-10 md:mx-32 lg:mx-52">
                        <div class="flex items-center gap-2 w-full bg-gray-300 dark:bg-gray-800 p-1 rounded"
                             v-for="(member, i) in room.members" :key="i">
                            <div class="flex-1 text-2xl text-black dark:text-white">
                                {{ member.name }}
                            </div>
                            <div>
                                <Checkbox class="size-8" :checked="member.status" :disabled="!isMyRoom"/>
                            </div>

                        </div>
                    </div>
                </main>

                <Footer/>
            </div>
        </div>
    </div>
</template>
