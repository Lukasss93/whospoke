<script setup lang="ts">
import {ref} from "vue";
import {DateTime} from "luxon";
import Digit from "@/Components/Digit.vue";
import {useIntervalFn} from '@vueuse/core';
import {trans} from "laravel-translator";

const props = withDefaults(defineProps<{
    started?: Date | null;
    ended?: Date | null;
}>(), {
    started: null,
    ended: null,
});

const time = ref(calcTime());

const {pause, resume, isActive} = useIntervalFn(() => {
    time.value = calcTime();
}, 1000);

function calcTime() {
    if (props.started === null) {
        return {minutes: '00', seconds: '00'};
    }

    const startedL = DateTime.fromJSDate(props.started);

    if (props.ended !== null) {
        const endedL = DateTime.fromJSDate(props.ended);
        const diff = endedL.diff(startedL, ['minutes', 'seconds']).toObject();

        return {
            minutes: diff.minutes?.toFixed(0).toString().padStart(2, '0') || '',
            seconds: diff.seconds?.toFixed(0).toString().padStart(2, '0') || '',
        };
    }

    const dt = DateTime.now().diff(startedL, ['minutes', 'seconds']).toObject();

    return {
        minutes: dt.minutes?.toFixed(0).toString().padStart(2, '0') || '',
        seconds: dt.seconds?.toFixed(0).toString().padStart(2, '0') || '',
    };
}
</script>

<template>
    <div class="text-2xl font-bold text-black dark:text-white font-mono">{{time.minutes}}:{{time.seconds}}</div>
</template>

<style scoped lang="scss">
</style>
