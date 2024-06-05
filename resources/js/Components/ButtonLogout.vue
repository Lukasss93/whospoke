<script setup lang="ts">
import {router} from '@inertiajs/vue3';
import axios from "axios";
import {trans} from "laravel-translator";

const props = withDefaults(defineProps<{
    redirect?: string | null;
    reload?: boolean;
}>(), {
    redirect: null,
    reload: false,
});

async function logout() {
    if (!props.reload) {
        router.visit(route('logout'), {
            method: 'post',
            data: {
                redirect: props.redirect
            }
        });
        return;
    }

    await axios.post(route('logout'), {redirect: props.redirect});
    location.reload();
}

</script>

<template>
    <button class="text-red-500 hover:underline" @click="logout">
        {{ trans('app.logout') }}
    </button>
</template>
