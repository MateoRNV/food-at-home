require('./bootstrap');

window.Vue = require('vue')

import Vue from 'vue'
import vuetify from './vuetify.js'
import VueRouter from 'vue-router'
import VueSocketIO from 'vue-socket.io'
import Toasted from 'vue-toasted'
import store from './stores/global-store'
import HomepageComponent from './components/homepage'
import MenuComponent from './components/menu'
import ManagerProductComponent from './components/manager/products'
import ManagerDashboardComponent from './components/manager/dashboard'
import LoginComponent from './components/login'
import RegisterComponent from './components/register'
import CookDashboardComponent from './components/cook-dashboard'
import UserComponent from './components/users'
import CartComponent from './components/cart'
import EditProfileComponent from './components/editProfile'
import DeliveryDashboardComponent from './components/delivery-dashboard'
import CustomerOrdersComponent from './components/orders'
import PageNotFound from './components/pageNotFound.vue'
import App from './App.vue'

Vue.use(VueRouter);

const routes = [
    { path: '/', component: HomepageComponent },
    { path: '/login', component: LoginComponent },
    { path: '/register', component: RegisterComponent },
    { path: '/menu', component: MenuComponent },
    { path: '/cart', component: CartComponent },
    { path: '/me/edit', component: EditProfileComponent },
    { path: '/me/orders', component: CustomerOrdersComponent },
    { path: '/cook/dashboard', component: CookDashboardComponent },
    { path: '/delivery/dashboard', component: DeliveryDashboardComponent },
    { path: '/manager/dashboard', component: ManagerDashboardComponent },
    { path: '/manager/products', component: ManagerProductComponent },
    { path: '/manager/users', component: UserComponent },
    { path: '*', component: PageNotFound },
]

const router = new VueRouter({
    // mode: 'history',
    // base: process.env.APP_URL,
    routes
})

Vue.use(new VueSocketIO({
    debug: true,
    connection: 'http://127.0.0.1:8080'
}))

Vue.use(Toasted, {
    position: 'bottom-right',
    duration: 5000,
    theme: 'outline',
    singleton: true,
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
        this.$store.dispatch('loadProducts')
        this.$store.dispatch('loadOrders')
    }
}).$mount('#app')

router.beforeEach((to, from, next) => {
    if(!store.state.user){
        if((to.path === '/cart') || (to.path === '/me/edit') || (to.path === '/me/orders') || (to.path === '/cook/dashboard') || (to.path === '/delivery/dashboard') || (to.path === '/manager/dashboard') || (to.path === '/manager/products') || (to.path === '/manager/users')){
            Vue.toasted.show('Please, login before proceeding', {type: 'error'})
            next('/login')
            return
        }
    }else{
        if((to.path === '/login')){
            Vue.toasted.show('You\'re already logged in', {type: 'warning'})
            next('/products')
            return
        }

        if(store.state.user.type !== 'C'){
            if((to.path === '/cart') || (to.path === '/me/orders')){
                Vue.toasted.show('Forbidden', {type: 'error'})
                next('/products')
            }
        }

        if(store.state.user.type !== 'EC'){
            if((to.path === '/cook/dashboard')){
                Vue.toasted.show('Forbidden', {type: 'error'})
                next('/products')
            }
        }

        if(store.state.user.type !== 'ED'){
            if((to.path === '/delivery/dashboard')){
                Vue.toasted.show('Forbidden', {type: 'error'})
                next('/products')
            }
        }

        if(store.state.user.type !== 'EM'){
            if((to.path === '/manager/dashboard') || (to.path === '/manager/products') || (to.path === '/manager/users')){
                Vue.toasted.show('Forbidden', {type: 'error'})
                next('/products')
            }
        }
    }

    next()
})
