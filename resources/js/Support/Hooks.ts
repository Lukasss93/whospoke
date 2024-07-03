import {ref, Ref, toValue, watchEffect} from "vue";
import {useIntervalFn} from '@vueuse/core';
import {calcTime} from "@/Support/Helpers";

export function useTimeCounter(start: CallableFunction | Ref | Date | null = null, end: CallableFunction | Ref | Date | null = null, interval: number = 1000) {
    const minutes = ref<string>('00');
    const seconds = ref<string>('00');

    function update() {
        const time = calcTime(toValue(start), toValue(end));

        minutes.value = time.minutes;
        seconds.value = time.seconds;
    }

    useIntervalFn(update, interval);

    watchEffect(() => {
        update();
    });

    return {minutes, seconds};
}
