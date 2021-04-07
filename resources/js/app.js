/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

import Vuetify from "../plugins/vuetify";
import vueRouter from "./routes";
import store from "./store";

// Automatically Register Components
const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

const app = new Vue({
    vuetify: Vuetify,
    router: vueRouter,
    store,
    el: '#app',
});
