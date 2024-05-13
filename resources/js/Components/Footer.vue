<script setup lang="ts">

</script>

<template>
    <footer class="py-10 text-center text-sm text-black dark:text-white/70">
        <a href="https://github.com/Lukasss93/whospoke"
           target="_blank"
           class="text-blue-500 hover:underline">
            {{ $page.props.appName }}
        </a> v{{ $page.props.appVersion }} -
        Developed by
        <a href="https://www.lucapatera.it"
           target="_blank"
           class="text-blue-500 hover:underline">
            Luca Patera
        </a>
    </footer>
</template>

<style scoped>

</style>
