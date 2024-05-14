<script setup lang="ts">
const props = withDefaults(defineProps<{
    size?: number;
    color?: string;
}>(), {
    size: 100,
    color: 'black',
});
</script>

<template>
    <div class="inline-block">
        <svg :width="props.size" :height="props.size" viewBox="0 0 24 24" fill="none"
             xmlns="http://www.w3.org/2000/svg">
            <path d="M4 12H20M12 4V20" :stroke="props.color" stroke-width="2" stroke-linecap="round"
                  stroke-linejoin="round"/>
        </svg>
    </div>
</template>
