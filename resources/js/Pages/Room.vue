<script setup lang="ts">
import {Head, usePage} from '@inertiajs/vue3';
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import {useDark} from '@vueuse/core'
import {computed} from "vue";
import {Room} from "@/types";
import Checkbox from "@/Components/Checkbox.vue";

const isDark = useDark();
const page = usePage();

const logoColor = computed(() => isDark.value ? 'white' : 'black');
const isLogged = computed(() => page.props.auth.user !== null);

defineProps<{
    room: Room;
}>();

</script>

<template>
    <Head :title="room.code"/>
    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <div
            class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white"
        >
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <header class="flex flex-col items-center gap-2 py-8 text-center">
                    <ApplicationLogo :color="logoColor"/>
                    <h4 class="text-black dark:text-white uppercase font-bold">Who Spoke?</h4>
                    <h1 class="text-3xl text-black dark:text-white uppercase font-bold">Chi ha parlato?</h1>
                </header>

                <main>
                    <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-2 sm:mx-10 md:mx-32 lg:mx-52">
                        <div class="flex items-center gap-2 w-full bg-gray-300 dark:bg-gray-800 p-1 rounded"
                             v-for="(member, i) in room.members" :key="i">
                            <div class="flex-1 text-2xl text-black dark:text-white">
                                {{ member.name }}
                            </div>
                            <div>
                                <Checkbox class="size-8" checked/>
                            </div>

                        </div>
                    </div>
                </main>

                <footer class="py-10 text-center text-sm text-black dark:text-white/70">
                    <a href="https://github.com/Lukasss93/whospoke"
                       target="_blank"
                       class="text-blue-500 hover:underline">
                        {{ page.props.appName }}
                    </a> v{{ page.props.appVersion }} -
                    Developed by
                    <a href="https://www.lucapatera.it"
                       target="_blank"
                       class="text-blue-500 hover:underline">
                        Luca Patera
                    </a>
                </footer>
            </div>
        </div>
    </div>
</template>
