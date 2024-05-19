import {ref} from "vue";
import {useIntervalFn} from '@vueuse/core';
import {DateTime} from "luxon";

export function useTimeCounter(start: Date | null = null, end: Date | null = null, interval: number = 1000) {
    const minutes = ref<string>('00');
    const seconds = ref<string>('00');

    function update() {
        if (start === null) {
            minutes.value = '00';
            seconds.value = '00';
            return;
        }

        const startedL = DateTime.fromJSDate(start);

        if (end !== null) {
            const endedL = DateTime.fromJSDate(end);
            const diff = endedL.diff(startedL, ['minutes', 'seconds']).toObject();

            minutes.value = diff.minutes?.toFixed(0).toString().padStart(2, '0') || '00';
            seconds.value = diff.seconds?.toFixed(0).toString().padStart(2, '0') || '00';
        }

        const dt = DateTime.now().diff(startedL, ['minutes', 'seconds']).toObject();

        minutes.value = dt.minutes?.toFixed(0).toString().padStart(2, '0') || '00';
        seconds.value = dt.seconds?.toFixed(0).toString().padStart(2, '0') || '00';
    }

    const {pause, resume, isActive} = useIntervalFn(update, interval);

    return {minutes, seconds};
}
