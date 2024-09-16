<script setup lang="ts">
defineProps<{
    title: string;
}>();
</script>

<template>
    <div class="rounded h-full bg-slate-800 border border-gray-700 min-h-15 grid auto-rows-fr">
        <div class="rounded text-balance text-2xs font-bold uppercase !leading-[0.6rem] text-center flex items-center justify-center bg-surface-900 text-white">
            {{title}}
        </div>
        <div class="text-center flex items-center justify-center">
            <slot/>
        </div>
    </div>
</template>

<style scoped lang="scss">

</style>
