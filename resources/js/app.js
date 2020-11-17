require('./bootstrap');

window.Vue = require('vue')

import vuetify from './vuetify.js'
import VueRouter from 'vue-router';
import ProductComponent from './components/products'
import LoginComponent from './components/login'
import App from './App.vue'

Vue.use(VueRouter);

const routes = [
    { path: '/', redirect: '/products' },
    { path: '/login', component: LoginComponent },
    { path: '/products', component: ProductComponent }
]

const router = new VueRouter({
    routes: routes
})

const app = new Vue({
    render: h => h(App),
    vuetify,
    router,
}).$mount('#app')