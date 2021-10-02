<template>
    <div id="navbar">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <router-link class="navbar-brand" to="/">Hamza e-Store App</router-link>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01"
                    aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarColor01">
                <ul class="navbar-nav mr-auto">
                    <li v-bind:class="[{active: currentPath === 'home', 'nav-item': true}]">
                        <router-link class="nav-link" to="/">Home</router-link>
                    </li>
                    <li v-bind:class="[{active: currentPath === 'product', 'nav-item': true}]">
                        <router-link class="nav-link" to="/products">Products</router-link>
                    </li>
                    <li v-bind:class="[{active: currentPath === 'order', 'nav-item': true}]">
                        <router-link class="nav-link" to="/orders">Orders</router-link>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</template>

<script>
    import { ActiveNavbarItem } from "../event-active-navbar-item";

    export default {
        data() {
            return {
                currentPath: 'home',
            }
        },

        created() {
            ActiveNavbarItem.$on("clicked-event", (source) => {
                this.currentPath = source;
            });
        }
    }
</script>

<style scoped>

</style>