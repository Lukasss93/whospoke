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
    <section class="clock">
        <div class="clock__col">
            <Digit :value="time.minutes"/>
            <div class="clock__unit">
                {{ trans('app.minutes') }}
            </div>
        </div>

        <div class="clock__col">
            <Digit :value="time.seconds"/>
            <div class="clock__unit">
                {{ trans('app.seconds') }}
            </div>
        </div>
    </section>
</template>

<style scoped lang="scss">
.clock {
    @apply relative grid grid-cols-2 gap-2;
}

.clock__col {
    @apply flex flex-col items-center justify-center;
    @apply bg-gray-300 dark:bg-gray-800;
    @apply border-gray-400 dark:border-gray-700;
    @apply border rounded-lg p-2;
}

.clock__unit {
    @apply text-gray-600 dark:text-gray-400 uppercase text-xs font-black mt-2;
}
</style>
