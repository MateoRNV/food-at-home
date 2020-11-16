require('./bootstrap');

window.Vue = require('vue')

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import ProductComponent from './components/products'
import IndexComponent from './components/index'

const routes = [
    { path: '/', redirect: '/index' },
    { path: 'index', component: IndexComponent },
    { path: '/products', component: ProductComponent }
]

const router = new VueRouter({
    routes: routes
})

import App from './App.vue'

new Vue({
    render: h => h(App),
    router
}).$mount('#app')