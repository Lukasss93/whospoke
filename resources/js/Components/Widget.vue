<script setup lang="ts">
defineProps<{
    title: string;
}>();
</script>

<template>
    <div class="rounded p-1 bg-surface-300 dark:bg-surface-800 border border-gray-400 dark:border-gray-700 min-h-20 grid auto-rows-fr">
        <div class="text-balance text-xs font-bold uppercase leading-none text-center flex items-center justify-center bg-black/10 text-black/80 dark:text-white/80 rounded">
            {{title}}
        </div>
        <div class="text-center flex items-center justify-center">
            <slot/>
        </div>
    </div>
</template>

<style scoped lang="scss">

</style>
