/* jshint esversion: 6 */

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example', require('./components/Example.vue'));

import Vue from 'vue';
import VeeValidate from 'vee-validate';
import toastr from 'toastr'
import App from './App.vue';
import router from './router';
import LoadingBar from './components/LoadingBar';
import Pagination from './components/Pagination';
import VueCarousel from 'vue-carousel';

Vue.component('App', require('./App.vue'));
Vue.component('toastr', require('toastr'));
Vue.component('LoadingBar', require('./components/LoadingBar'));
Vue.component('Pagination', require('./components/Pagination'));

Vue.use(VeeValidate);
Vue.use(VueCarousel);

/* eslint-disable no-new */
const app = new Vue({
    el: '#app',
    router,
    template: '<App/>',
    components: { App },
});
