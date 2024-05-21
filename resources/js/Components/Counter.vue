<script setup lang="ts">

import DangerButton from "@/Components/DangerButton.vue";
import SuccessButton from "@/Components/SuccessButton.vue";

withDefaults(defineProps<{
    canEdit: boolean;
}>(), {
    canEdit: false,
});

const counter = defineModel<number>({default: 0});

defineEmits(['reset', 'decrement', 'increment']);

</script>

<template>
    <div class="flex gap-1">
        <DangerButton class="!px-1" @click="$emit('reset')" v-if="canEdit">
            <font-awesome-icon icon="fa-solid fa-rotate-left" fixed-width/>
        </DangerButton>
        <DangerButton class="!px-1" @click="$emit('decrement')" v-if="canEdit">
            <font-awesome-icon icon="fa-solid fa-minus"/>
        </DangerButton>
        <SuccessButton class="!px-1" @click="$emit('increment')" v-if="canEdit">
            <font-awesome-icon icon="fa-solid fa-plus"/>
        </SuccessButton>

        <span
            class="bg-gray-400 dark:bg-gray-900 px-2 text-xl text-black dark:text-white font-mono rounded inline-block">
            {{ counter }}
        </span>
    </div>
</template>

<style scoped lang="scss">

</style>
