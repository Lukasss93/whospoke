<script setup lang="ts">
import {LoginWidget} from "vue-tg";
import Button from 'primevue/button';
import {router} from "@inertiajs/vue3";
import axios from "axios";
import {computed} from "vue";
import {trans} from "laravel-translator";

const props = withDefaults(defineProps<{
    redirect?: string | null;
    reload?: boolean;
    size?: "small" | "medium" | "large";
}>(), {
    redirect: null,
    reload: false,
    size: "large",
});

const sizeForButton = computed(() => props.size === "medium" ? undefined : props.size);

function loginWithSlack() {
    location.href = route('login.slack.redirect', {redirect: props.redirect});
}

async function localLogin() {
    if (!props.reload) {
        router.visit(route('login.local', {redirect: props.redirect}));
        return;
    }

    await axios.post(route('login.local', {redirect: props.redirect}));
    location.reload();
}

</script>

<template>
    <div class="inline-flex flex-col gap-2 items-center">
        <!-- TELEGRAM LOGIN -->
        <LoginWidget v-if="!$page.props.app.isLocal && $page.props.auth.modes.telegram"
                     :bot-username="$page.props.auth.botUsername"
                     :redirect-url="route('login.telegram', {redirect: props.redirect})"
                     corner-radius="5"
                     :size="size"
                     :user-photo="false"/>

        <!-- SLACK LOGIN -->
        <Button v-if="!$page.props.app.isLocal && $page.props.auth.modes.slack"
                class="w-full font-medium"
                :class="{'text-xs !py-1 !px-2': size === 'small', '!text-lg !py-1': size === 'large'}"
                :size="sizeForButton"
                severity="help"
                @click="loginWithSlack">
            <i class="pi pi-slack mr-1"></i>{{ trans('app.login.slack') }}
        </Button>

        <!-- LOCAL LOGIN -->
        <Button v-if="$page.props.app.isLocal"
                :size="sizeForButton"
                class="text-xs !py-1 !px-8"
                label="Local Login"
                severity="warning"
                @click="localLogin"/>
    </div>
</template>

<style scoped lang="scss">

</style>
