<script lang="ts" setup>
defineProps<{
    value: string;
}>();
</script>

<template>
    <div class="inline-block">
        <div class="clock__digit">
            <Transition mode="out-in" name="change" class="clock__digit_text">
                <div :key="value.charAt(0)">{{ value.charAt(0) }}</div>
            </Transition>
        </div>
        <div class="clock__digit">
            <Transition mode="out-in" name="change" class="clock__digit_text">
                <div :key="value.charAt(1)">{{ value.charAt(1) }}</div>
            </Transition>
        </div>
    </div>
</template>

<style scoped lang="scss">
.clock__digit {
    @apply font-mono inline-block mx-0.5;
    @apply text-xl font-bold;
}

.clock__digit_text {
    @apply bg-gray-400 dark:bg-gray-700 rounded px-3 py-1;
    @apply text-gray-700 dark:text-gray-300;
}

.clock__digit > div {
    @apply relative inline-block align-top;
}

.change-leave-active,
.change-enter-active {
    transition: all 200ms ease;
}

.change-enter-from {
    transform: translateY(-20px);
    opacity: 0;
}

.change-leave-to {
    transform: translateY(12px);
    opacity: 0;
}
</style>
