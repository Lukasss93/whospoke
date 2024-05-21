<script setup lang="ts">
const props = defineProps<{
    group: string;
    label: string;
    value: string;
}>();

const selected = defineModel<string>();

function clickOnRadio() {
    selected.value = props.value;
}

</script>

<template>
    <div class="flex items-center">
        <input class="cursor-pointer" type="radio" :name="group" :value v-model="selected">
        <label class="cursor-pointer text-gray-600 dark:text-gray-400 mx-1" @click="clickOnRadio">
            {{ label }}
        </label>
    </div>
</template>

<style scoped lang="scss">

</style>
