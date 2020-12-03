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
import App from './App.vue'

Vue.use(VueRouter);
Vue.use(Toasted, {
    position: 'bottom-right',
    duration: 5000,
    type: 'info'
})


const routes = [
    // { path: '/', redirect: '/products' },
    { path: '/', component: HomepageComponent },
    { path: '/login', component: LoginComponent },
    { path: '/register', component: RegisterComponent },
    { path: '/products', component: ProductComponent },
    { path: '/users', component: UserComponent },
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
    store,
    created(){
        this.$store.dispatch('loadUserLogged')
        this.$store.dispatch('loadUsers')
    }
}).$mount('#app')
 
router.beforeEach((to, from, next) => {
    console.log(to)
    if(!store.state.user){
        if((to.path === '/users')){
            console.log("Não tem permissoes")
            next(false)
            return
        }
    }
    next()
})