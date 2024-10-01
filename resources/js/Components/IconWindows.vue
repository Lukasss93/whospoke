<script setup lang="ts">
const props = withDefaults(defineProps<{
    size?: number;
}>(), {
    size: 14,
});
</script>

<template>
    <div class="inline-block align-middle">
        <svg xmlns="http://www.w3.org/2000/svg" :width="props.size" :height="props.size" viewBox="0 0 22 22">
            <rect x="12" y="12" width="10" height="10" fill="#feba08" />
            <rect x="0" y="12" width="10" height="10" fill="#05a6f0" />
            <rect x="12" y="0" width="10" height="10" fill="#80bc06" />
            <rect x="0" y="0" width="10" height="10" fill="#f25325" />
        </svg>
    </div>
</template>
