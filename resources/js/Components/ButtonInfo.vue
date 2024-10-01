<script setup lang="ts">
const props = withDefaults(defineProps<{
    disabled?: boolean;
}>(), {
    disabled: false,
});
</script>

<template>
    <button :disabled="props.disabled" class="info-button">
        <slot />
    </button>
</template>

<style scoped lang="scss">
.info-button {
    @apply inline-flex items-center px-4 py-2;
    @apply bg-blue-600 border border-transparent rounded-md;
    @apply font-semibold text-xs text-white uppercase tracking-widest;
    @apply hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150;

    &:disabled {
        @apply bg-gray-400 dark:bg-gray-700 text-gray-500 dark:text-gray-300 cursor-not-allowed;
    }
}
</style>
