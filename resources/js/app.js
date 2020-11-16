require('./bootstrap');

window.Vue = require('vue')

import Vuetify from 'vuetify'
Vue.use(Vuetify);

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import ProductComponent from './components/products'
import LoginComponent from './components/login'

const routes = [
    { path: '/', redirect: '/products' },
    { path: '/login', component: LoginComponent },
    { path: '/products', component: ProductComponent }
]

const router = new VueRouter({
    routes: routes
})

import App from './App.vue'

const app = new Vue({
    render: h => h(App),
    vuetify: new Vuetify(),
    router
}).$mount('#app')