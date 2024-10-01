<script setup lang="ts">
import {trans} from "laravel-translator";
import SelectButton from 'primevue/selectbutton';
import { useColorMode } from '@vueuse/core';
import {Tippy} from "vue-tippy";
import {ref} from "vue";
import Tag from 'primevue/tag';
import IconWindows from "@/Components/IconWindows.vue";
import IconLinux from "@/Components/IconLinux.vue";
import IconMac from "@/Components/IconMac.vue";

const locale = document.getElementsByTagName('html')[0].getAttribute('lang');

const langs = [
    {code: 'it', name: 'Italian', country: 'it'},
    {code: 'en', name: 'English', country: 'gb'},
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
        <!-- CREDITS -->
        <div class="flex justify-center gap-1">
            <a :href="$page.props.developer.github" target="_blank" class="text-blue-500 hover:underline">
                {{ $page.props.app.name }}
            </a>
            <Tag severity="info">v{{ $page.props.app.version }}</Tag>
            <span>·</span>
            <span>
                {{ trans('app.developed') }}
                <a href="https://www.lucapatera.it" target="_blank" class="text-blue-500 hover:underline">
                    {{ $page.props.developer.name }}
                </a>
            </span>
        </div>

        <!-- DOWNLOAD APP -->
        <a href="https://github.com/Lukasss93/whospoke-companion/releases" target="_blank" class="block text-blue-500 hover:underline">
            Scarica l'app desktop per <IconWindows/> Windows, <IconLinux/> Linux e <IconMac/> macOS
        </a>

        <!-- LANGUAGES -->
        <div class="inline-flex gap-1">
            <template v-for="({code, name, country}, index) in langs">
                <a :href="route('locale.set', {locale: code})"
                   class="lang-button inline-flex justify-center items-center gap-0.5" :data-active="locale===code">
                    <country-flag :country='country' size='small' />
                    <span>{{ name }}</span>
                </a>
                <span v-if="index+1 !== langs.length">&nbsp;·&nbsp;</span>
            </template>
        </div>
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
