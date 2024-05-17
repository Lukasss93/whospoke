import {PageProps as InertiaPageProps} from '@inertiajs/core';
import {AxiosInstance} from 'axios';
import {route as ziggyRoute} from 'ziggy-js';
import {PageProps as AppPageProps} from './';
import Echo from "laravel-echo";
//import {Config} from "laravel-translator/translator";

declare global {
    interface Window {
        axios: AxiosInstance;
        Echo: Echo;
    }

    var route: typeof ziggyRoute;
}

declare module 'vue' {
    interface ComponentCustomProperties {
        route: typeof ziggyRoute;
    }
}

declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps, AppPageProps {}
}

declare module '@vue/runtime-core' {
    interface ComponentCustomProperties {
        trans: (key: string, replace?: object, locale?: string, config?: Config) => string;
        transChoice: (key: string, number: number, replace?: Object, locale?: string, config?: Config) => string;
        __: (key: string, replace?: object, locale?: string, config?: Config) => string;
        t: (key: string, replace?: object, locale?: string, config?: Config) => string;
        trans_choice: (key: string, number: number, replace?: Object, locale?: string, config?: Config) => string;
    }
}
