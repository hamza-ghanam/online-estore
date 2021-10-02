require('./bootstrap');
import Vue from 'vue';
import VueRouter from 'vue-router';
import { routes } from './routes';
import '@fortawesome/fontawesome-free/css/all.css';
import '@fortawesome/fontawesome-free/js/all.js';

Vue.use(VueRouter)

window.Vue = require('vue').default;

Vue.component('navbar-component', require('./components/navbar.vue').default);
Vue.component('app-component', require('./components/app.vue').default);

const router = new VueRouter({
    mode: 'history',
    routes
});

const app = new Vue({
    el: '#app',
    router,
});
