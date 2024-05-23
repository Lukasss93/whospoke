import './bootstrap';
import '../css/app.css';
import 'vue3-toastify/dist/index.css';
import {createApp, DefineComponent, h} from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {ZiggyVue} from '../../vendor/tightenco/ziggy';
import Vue3Toastify from 'vue3-toastify';
import VueTippy from 'vue-tippy';
import 'tippy.js/dist/tippy.css';
import 'tippy.js/themes/translucent.css';
import 'tippy.js/themes/material.css';
import 'tippy.js/themes/light-border.css';
import 'tippy.js/themes/light.css';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import {library} from '@fortawesome/fontawesome-svg-core';
import * as faSolidIcons from '@fortawesome/free-solid-svg-icons';
import PrimeVue from 'primevue/config';
import Lara from '@/presets/lara';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

library.add(faSolidIcons.faPlay);
library.add(faSolidIcons.faStop);
library.add(faSolidIcons.faRotateLeft);
library.add(faSolidIcons.faXmark);
library.add(faSolidIcons.faPlus);
library.add(faSolidIcons.faEye);
library.add(faSolidIcons.faEyeSlash);
library.add(faSolidIcons.faPlus);
library.add(faSolidIcons.faMinus);

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob<DefineComponent>('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .component('font-awesome-icon', FontAwesomeIcon)
            .use(plugin)
            .use(ZiggyVue)
            .use(Vue3Toastify, {
                autoClose: 3000,
                theme: 'auto',
            })
            .use(VueTippy)
            .use(PrimeVue, {
                unstyled: true,
                pt: Lara,
            })
            .mount(el);
    },
    progress: {
        color: '#005cd5',
    },
});
