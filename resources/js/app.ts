import './bootstrap';
import {createApp, DefineComponent, h} from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {ZiggyVue} from '../../vendor/tightenco/ziggy';
import Vue3Toastify from 'vue3-toastify';
import VueTippy from 'vue-tippy';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import {library} from '@fortawesome/fontawesome-svg-core';
import * as faSolidIcons from '@fortawesome/free-solid-svg-icons';
import {MotionPlugin} from '@vueuse/motion';
import VueAnimXYZ from '@animxyz/vue3';
import PrimeVue from 'primevue/config';
import Aura from '@/presets/aura';
import ConfirmationService from 'primevue/confirmationservice';
import ToastService from 'primevue/toastservice';
import 'vue3-toastify/dist/index.css';
import 'tippy.js/dist/tippy.css';
import 'tippy.js/themes/translucent.css';
import 'tippy.js/themes/material.css';
import 'tippy.js/themes/light-border.css';
import 'tippy.js/themes/light.css';
import '@animxyz/core';
import 'primeicons/primeicons.css';
import '../css/app.scss';
import Tooltip from 'primevue/tooltip';
import { usePassThrough } from "primevue/passthrough";
import CountryFlag from 'vue-country-flag-next';

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
library.add(faSolidIcons.faCircleNotch);
library.add(faSolidIcons.faUser);
library.add(faSolidIcons.faBroadcastTower);

const CustomPreset = usePassThrough(
    Aura,
    {
        directives: {
            tooltip: {
                text: {
                    class: ['!text-xs !py-1 !px-2']
                }
            }
        }
    },
    {
        mergeSections: true,
        mergeProps: true,
    }
);

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob<DefineComponent>('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .component('font-awesome-icon', FontAwesomeIcon)
            .component('country-flag', CountryFlag)
            .use(plugin)
            .use(MotionPlugin)
            .use(VueAnimXYZ)
            .use(ZiggyVue)
            .use(Vue3Toastify, {
                autoClose: 3000,
                theme: 'auto',
            })
            .use(VueTippy)
            .use(PrimeVue, {
                unstyled: true,
                pt: CustomPreset,
                ptOptions: {
                    mergeSections: true,
                    mergeProps: true,
                }
            })
            .use(ConfirmationService)
            .use(ToastService);

        app.directive('tooltip', Tooltip);

        app.mount(el);

        return app;
    },
    progress: {
        color: '#005cd5',
    },
});
