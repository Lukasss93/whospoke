<script setup lang="ts">
import {Head, Link, usePage} from '@inertiajs/vue3';
import {LoginWidget} from 'vue-tg';
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import {useDark} from '@vueuse/core'
import {computed, ref} from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const isDark = useDark();
const page = usePage();

const logoColor = computed(() => isDark.value ? 'white' : 'black');
const isLogged = computed(() => page.props.auth.user !== null);
const isCreatingSession = ref(false);

defineProps<{
    appName: string;
    appVersion: string;
}>();

</script>

<template>
    <Head title="Home Page"/>
    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <div
            class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white"
        >
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <header class="flex flex-col items-center gap-2 py-10">
                    <ApplicationLogo :color="logoColor"/>
                    <h4 class="text-black dark:text-white uppercase font-bold">Who Spoke?</h4>
                    <h1 class="text-3xl text-black dark:text-white uppercase font-bold">Chi ha parlato?</h1>
                </header>

                <main>

                    <div class="flex flex-col items-center gap-2">

                        <LoginWidget
                            v-if="!isLogged"
                            bot-username="WhoSpokeBot"
                            :redirect-url="route('access')"
                            corner-radius="8"
                            :user-photo="false"
                        />

                        <PrimaryButton v-if="isLogged">
                            Crea una sessione
                        </PrimaryButton>
                        <p class="text-red-500" v-if="!isLogged">Per creare una sessione, devi prima eseguire il
                            login.</p>

                        <p class="text-xl">Per unirti in una sessione, apri un link diretto alla sessione.</p>

                        <Link :href="route('logout')" method="post"
                              v-if="isLogged"
                              as="button"
                              type="button"
                              class="text-red-500 hover:underline">
                            Logout
                        </Link>

                    </div>
                </main>

                <footer class="py-10 text-center text-sm text-black dark:text-white/70">
                    <a href="https://github.com/Lukasss93/whospoke"
                       target="_blank"
                       class="text-blue-500 hover:underline">
                        {{ appName }}
                    </a> v{{ appVersion }} -
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
