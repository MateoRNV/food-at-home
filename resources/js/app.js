require('./bootstrap');

window.Vue = require('vue')

import Vue from 'vue'
import vuetify from './vuetify.js'
import VueRouter from 'vue-router'
import Toasted from 'vue-toasted'
import store from './stores/global-store'
import HomepageComponent from './components/homepage'
import ProductComponent from './components/products'
import LoginComponent from './components/login'
import RegisterComponent from './components/register'
import UserComponent from './components/users'
import CartComponent from './components/cart'
import DeliveryDashboardComponent from './components/delivery-dashboard'
import App from './App.vue'

Vue.use(VueRouter);

const routes = [
    // { path: '/', redirect: '/products' },
    { path: '/', component: HomepageComponent },
    { path: '/login', component: LoginComponent },
    { path: '/register', component: RegisterComponent },
    { path: '/products', component: ProductComponent },
    { path: '/users', component: UserComponent },
    { path: '/cart', component: CartComponent },
    { path: '/delivery/dashboard', component: DeliveryDashboardComponent },
]

const router = new VueRouter({
    // mode: 'history',
    // base: process.env.APP_URL,
    routes
})

Vue.use(Toasted, {
    position: 'bottom-right',
    duration: 3000,
    type: 'info',
    singleton: true,
    theme: 'outline',
    router
})

const app = new Vue({
    render: h => h(App),
    vuetify,
    router,
    store,
    created(){
        this.$store.dispatch('loadUserLogged')
        this.$store.dispatch('loadUsers')
        //this.$store.dispatch('rebuildCartFromStorage')
    }
}).$mount('#app')
 
router.beforeEach((to, from, next) => {
    if(!store.state.user){
        if((to.path === '/users') || (to.path === '/cart')){
            Vue.toasted.show('You must login first', {type: 'error'})
            next('/login')
            return
        }
    }else{
        if((to.path === '/login')){
            console.log('User already logged in')
            next('/products')
            return
        }
    }

    next()
})