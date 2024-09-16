<script setup lang="ts">
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import {useDateFormat, useNow} from "@vueuse/core";
import {Link} from '@inertiajs/vue3';
import {trans} from "laravel-translator";

withDefaults(defineProps<{
    title?: string | null;
}>(), {
    title: trans('app.name'),
});

const locale = document.getElementsByTagName('html')[0].getAttribute('lang') || window.fallbackLocale;
const currentTime = useDateFormat(useNow(), 'dddd, DD MMMM YYYY HH:mm:ss', {locales: locale});
</script>

<template>
    <header class="flex flex-col items-center text-center">
        <div class="flex items-center gap-2">
            <Link :href="route('home')" class="flex">
                <ApplicationLogo color="white"
                                 :size="35"
                                 class="transition-all duration-300 ease-in-out hover:scale-110"/>
            </Link>
            <h1 class="text-lg text-white uppercase font-bold">
                {{ title || trans('app.name') }}
            </h1>
        </div>
        <p class="text-xs font-mono uppercase font-bold text-gray-400 dark:text-gray-400">{{ currentTime }}</p>
    </header>
</template>

<style scoped>

</style>
