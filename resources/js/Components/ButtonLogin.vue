<script setup lang="ts">
import {LoginWidget} from "vue-tg";
import Button from 'primevue/button';
import {router} from "@inertiajs/vue3";
import axios from "axios";

const props = withDefaults(defineProps<{
    redirect?: string | null;
    reload?: boolean;
}>(), {
    redirect: null,
    reload: false,
});

async function localLogin() {
    if (!props.reload) {
        router.visit(route('local.login', {redirect: props.redirect}));
        return;
    }

    await axios.post(route('local.login', {redirect: props.redirect}));
    location.reload();
}

</script>

<template>
    <div>
        <LoginWidget v-if="!$page.props.app.isLocal"
                     :bot-username="$page.props.auth.botUsername"
                     :redirect-url="route('access', {redirect: props.redirect})"
                     corner-radius="5"
                     size="small"
                     :user-photo="false"/>

        <Button v-if="$page.props.app.isLocal"
                size="small"
                class="text-xs !py-1 !px-8"
                label="Local Login"
                severity="warning"
                @click="localLogin"/>
    </div>
</template>

<style scoped lang="scss">

</style>
