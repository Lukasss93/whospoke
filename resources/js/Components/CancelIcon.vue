<script setup lang="ts">
const props = withDefaults(defineProps<{
    size: number;
    color: string;
}>(), {
    size: 100,
    color: 'black',
});
</script>

<template>
    <div class="inline-block">
        <svg :width="props.size" :height="props.size" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g id="work-case" :fill="props.color" transform="translate(91.520000, 91.520000)">
                    <polygon id="Close"
                             points="328.96 30.2933333 298.666667 1.42108547e-14 164.48 134.4 30.2933333 1.42108547e-14 1.42108547e-14 30.2933333 134.4 164.48 1.42108547e-14 298.666667 30.2933333 328.96 164.48 194.56 298.666667 328.96 328.96 298.666667 194.56 164.48"/>
                </g>
            </g>
        </svg>

    </div>
</template>
