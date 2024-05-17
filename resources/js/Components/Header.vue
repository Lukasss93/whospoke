<script setup lang="ts">
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import {computed} from "vue";
import {useDark} from "@vueuse/core";
import {Link} from '@inertiajs/vue3';
import {trans} from "laravel-translator";

const isDark = useDark();
const logoColor = computed(() => isDark.value ? 'white' : 'black');
</script>

<template>
    <header class="flex flex-col items-center gap-2 py-8 text-center">
        <Link :href="route('home')">
            <ApplicationLogo :color="logoColor" class="transition-all duration-300 ease-in-out hover:scale-110"/>
        </Link>
        <h1 class="text-3xl text-black dark:text-white uppercase font-bold">
            {{ trans('app.name') }}
        </h1>
    </header>
</template>

<style scoped>

</style>
