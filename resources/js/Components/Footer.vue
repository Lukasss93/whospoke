<script setup lang="ts">
import {trans} from "laravel-translator";
import SelectButton from 'primevue/selectbutton';
import { useColorMode } from '@vueuse/core';
import {Tippy} from "vue-tippy";
import {ref} from "vue";

const locale = document.getElementsByTagName('html')[0].getAttribute('lang');

const langs = [
    {code: 'it', name: '🇮🇹 Italian'},
    {code: 'en', name: '🇺🇸 English'},
];


const allowedThemes = ref([
    {value: 'auto', icon: 'pi pi-desktop', tooltip: trans('app.theme.auto')},
    {value: 'light', icon: 'pi pi-sun', tooltip: trans('app.theme.light')},
    {value: 'dark', icon: 'pi pi-moon', tooltip: trans('app.theme.dark')},
]);
const { system, store } = useColorMode();
</script>

<template>

    <Teleport to="body">
        <SelectButton :model-value="store"
                      @update:model-value="store = $event"
                      :options="allowedThemes"
                      optionLabel="value"
                      optionValue="value"
                      class="absolute top-0 right-0 m-2"
                      pt:button:class="!px-3 border-y first:border-l first:border-tr-none first:border-br-none last:border-tl-none last:border-bl-none last:border-r border-red-500 !border-surface-0 dark:!border-surface-800"
                      dataKey="value">
            <template #option="slotProps">
                <tippy :content="slotProps.option.tooltip" placement="bottom">
                    <i :class="slotProps.option.icon"></i>
                </tippy>
            </template>
        </SelectButton>
    </Teleport>


    <footer class="pt-5 text-center text-sm text-black dark:text-white">
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

<style scoped lang="scss">
.lang-button {
    &[data-active="false"] {
        @apply cursor-pointer text-blue-500 hover:underline;
    }

    &[data-active="true"] {
        @apply cursor-default text-gray-600;
    }
}
</style>
