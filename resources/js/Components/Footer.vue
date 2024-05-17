<script setup lang="ts">
import {useStorage} from '@vueuse/core';
import {setLocale} from "laravel-translator";
import {watch} from "vue";

const locale = useStorage('lang', 'en');
watch(locale, (value) => setLocale(value));

</script>

<template>
    <footer class="py-10 text-center text-sm text-black dark:text-white/70">
        <p>
            <a href="https://github.com/Lukasss93/whospoke"
               target="_blank"
               class="text-blue-500 hover:underline">
                {{ $page.props.appName }}
            </a> v{{ $page.props.appVersion }} -
            Developed by
            <a href="https://www.lucapatera.it"
               target="_blank"
               class="text-blue-500 hover:underline">
                Luca Patera
            </a>
        </p>
        <p>
            <button class="lang-button" :data-active="locale==='it'" @click="locale='it'">🇮🇹 Italian</button> |
            <button class="lang-button" :data-active="locale==='en'" @click="locale='en'">🇺🇸 English</button>
        </p>
    </footer>
</template>

<style scoped>
.lang-button{
    &[data-active="false"]{
        @apply cursor-pointer text-blue-500 hover:underline;
    }

    &[data-active="true"]{
        @apply cursor-default text-gray-600;
    }
}
</style>
