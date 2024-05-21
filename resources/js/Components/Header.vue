<script setup lang="ts">
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import {computed} from "vue";
import {useDark, useDateFormat, useNow} from "@vueuse/core";
import {Link} from '@inertiajs/vue3';
import {trans} from "laravel-translator";

const isDark = useDark();
const logoColor = computed(() => isDark.value ? 'white' : 'black');
const locale = document.getElementsByTagName('html')[0].getAttribute('lang') || window.fallbackLocale;
const currentTime = useDateFormat(useNow(), 'dddd, DD MMMM YYYY HH:mm:ss', {locales: locale})
</script>

<template>
    <header class="flex flex-col items-center gap-1 pb-4 text-center">
        <Link :href="route('home')">
            <ApplicationLogo :color="logoColor" class="transition-all duration-300 ease-in-out hover:scale-110"/>
        </Link>
        <h1 class="text-3xl text-black dark:text-white uppercase font-bold">
            {{ trans('app.name') }}
        </h1>
        <p class="font-mono uppercase font-bold text-gray-600 dark:text-gray-400">{{ currentTime }}</p>
    </header>
</template>

<style scoped>

</style>
