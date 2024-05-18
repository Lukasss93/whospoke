<script setup lang="ts">
import {trans} from "laravel-translator";

const locale = document.getElementsByTagName('html')[0].getAttribute('lang');

const langs = [
    {code: 'it', name: '🇮🇹 Italian'},
    {code: 'en', name: '🇺🇸 English'},
];

</script>

<template>
    <footer class="pt-5 text-center text-sm text-black dark:text-white/70">
        <p>
            <a :href="$page.props.developer.github" target="_blank" class="text-blue-500 hover:underline">
                {{ $page.props.app.name }}
            </a>
            v{{ $page.props.app.version }}
            -
            {{ trans('app.developed') }}
            <a href="https://www.lucapatera.it" target="_blank" class="text-blue-500 hover:underline">
                {{ $page.props.developer.name }}
            </a>
        </p>
        <p>
            <span v-for="({code, name}, index) in langs">
                <a :href="route('locale.set', {locale: code})"
                   class="lang-button" :data-active="locale===code">
                    {{ name }}
                </a>
                 <span v-if="index+1 !== langs.length">&nbsp;|&nbsp;</span>
            </span>
        </p>
    </footer>
</template>

<style scoped>
.lang-button {
    &[data-active="false"] {
        @apply cursor-pointer text-blue-500 hover:underline;
    }

    &[data-active="true"] {
        @apply cursor-default text-gray-600;
    }
}
</style>
