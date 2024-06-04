<script setup lang="ts">
import {computed} from 'vue';

const emit = defineEmits(['update:checked']);

const props = withDefaults(defineProps<{
    checked: boolean;
    value?: any;
    disabled?: boolean;
    loadingWhenUnchecked?: boolean;
}>(), {
    value: null,
    disabled: false,
    loadingWhenUnchecked: false,
});

const proxyChecked = computed({
    get() {
        return props.checked;
    },

    set(val) {
        emit('update:checked', val);
    },
});
</script>

<template>
    <div class="relative flex justify-center items-center">
        <font-awesome-icon v-if="loadingWhenUnchecked && !checked"
                           icon="fa-solid fa-circle-notch"
                           size="lg"
                           spin
                           class="absolute text-white"/>

        <input
            type="checkbox"
            :value="value"
            :disabled="disabled"
            v-model="proxyChecked"
            class="size-full cursor-pointer disabled:cursor-default rounded bg-red-700 dark:bg-red-900 border-red-500 dark:border-red-700 text-green-600 shadow-sm focus:ring-gray-500 dark:focus:ring-gray-600 dark:focus:ring-offset-gray-800"
        />
    </div>
</template>
