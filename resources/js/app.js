/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import Index from './index'
import 'es6-promise/auto'
import axios from 'axios'
import './bootstrap'
import Vue from 'vue'
import VueAuth from '@websanova/vue-auth'
import VueAxios from 'vue-axios'
import VueRouter from 'vue-router'
import auth from './auth'
import router from './router'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import VueSidebarMenu from 'vue-sidebar-menu'
import 'vue-sidebar-menu/dist/vue-sidebar-menu.css'
import Treeselect from '@riophae/vue-treeselect'
import '@riophae/vue-treeselect/dist/vue-treeselect.css'
import VueTagsInput from '@johmun/vue-tags-input';
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';

window.Vue = require('vue');
Vue.router = router;
Vue.use(VueRouter);
Vue.use(VueAxios, axios);
// axios.defaults.baseURL = `${process.env.MIX_APP_URL}`
Vue.use(VueAuth, auth);
Vue.use(BootstrapVue);
Vue.use(VueSidebarMenu);
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
// Load Index
Vue.component('index', Index);
Vue.component('treeselect', Treeselect);
Vue.component('tags', VueTagsInput);
Vue.component('date-picker', DatePicker);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router
});

window.EventBus = new Vue();