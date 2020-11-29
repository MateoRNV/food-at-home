require('./bootstrap');

window.Vue = require('vue')

import Vue from 'vue'
import vuetify from './vuetify.js'
import VueRouter from 'vue-router';
import HomepageComponent from './components/homepage'
import ProductComponent from './components/products'
import LoginComponent from './components/login'
import RegisterComponent from './components/register'
import App from './App.vue'

Vue.use(VueRouter);

const routes = [
    // { path: '/', redirect: '/products' },
    { path: '/', component: HomepageComponent },
    { path: '/login', component: LoginComponent },
    { path: '/register', component: RegisterComponent },
    { path: '/products', component: ProductComponent },
]

const router = new VueRouter({
    // mode: 'history',
    // base: process.env.BASE_URL,
    routes
})

const app = new Vue({
    render: h => h(App),
    vuetify,
    router,
}).$mount('#app')
