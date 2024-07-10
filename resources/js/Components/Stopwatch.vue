<script setup lang="ts">
import {ref} from "vue";
import {useIntervalFn} from '@vueuse/core';
import {calcTime} from "@/Support/Helpers";

const props = withDefaults(defineProps<{
    started?: Date | null;
    ended?: Date | null;
}>(), {
    started: null,
    ended: null,
});

const time = ref(calcTime(props.started, props.ended));

useIntervalFn(() => {
    time.value = calcTime(props.started, props.ended);
}, 1000);
</script>

<template>
    <div class="text-3xl font-bold text-black dark:text-white font-mono">{{ time.minutes }}:{{ time.seconds }}</div>
</template>

<style scoped lang="scss">
</style>
