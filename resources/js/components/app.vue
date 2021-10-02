<template>
    <div id="body">
        <navbar-component/>
        <div class="container-fluid mt-4">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>

    export default {
        components: {}
    }
</script>

<style scoped>
    .container-fluid {
        width: 95% !important;
    }
</style>