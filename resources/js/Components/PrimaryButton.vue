<script setup lang="ts">
const props = withDefaults(defineProps<{
    disabled?: boolean;
}>(), {
    disabled: false,
});
</script>

<template>
    <button :disabled="props.disabled" class="primary-button">
        <slot />
    </button>
</template>

<style scoped lang="scss">
.primary-button {
    @apply inline-flex items-center px-4 py-2;
    @apply bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md;
    @apply font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest;
    @apply hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150;

    &:disabled {
        @apply bg-gray-300 dark:bg-gray-700 text-gray-500 dark:text-gray-300 cursor-not-allowed;
    }
}
</style>
