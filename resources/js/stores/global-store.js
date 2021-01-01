import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

let cart = window.sessionStorage.getItem('cart');
let cartCount = window.sessionStorage.getItem('cartCount');
let currentOrder = window.sessionStorage.getItem('currentOrder')

export default new Vuex.Store({
    state: {
        user: null,
        users: [],
        products: [],
        orders: [],

        cart: cart ? JSON.parse(cart) : [],
        cartCount: cartCount ? parseInt(cartCount) : 0,

        currentOrder: currentOrder ? JSON.parse(currentOrder) : ''
    },
    mutations: {
        CLEAR_USER(state){
            state.user = null
        },
        SET_USER (state, user){
            state.user = user
        },
        SET_USER_LIST(state, users){
            state.users = users
        },
        SET_PRODUCTS_LIST(state, products){
            state.products = products
        },
        SET_ORDERS_LIST(state, orders){
            state.orders = orders
        },
        SET_CURRENT_ORDER(state, order){
            state.currentOrder = order
            sessionStorage.setItem('currentOrder', JSON.stringify(state.currentOrder))
        },
        CLEAR_CURRENT_ORDER(state){
            state.currentOrder = ''
            sessionStorage.removeItem('currentOrder')
        },
        ADD_PRODUCT_TO_LIST(state, product){
            state.products.push(product)
        },
        REMOVE_PRODUCT_FROM_LIST(state, product){
            const index = state.products.indexOf(product)

            if(index > -1){
                state.products.splice(index, 1)
            }
        },
        UPDATE_PRODUCT_FROM_LIST(state, product){
            const index = state.products.findIndex(item => item.id === product.id)

            if(index > -1){
                state.products.splice(index, 1, product)
            }
        },
        ADD_ITEM_TO_CART(state, item){
            let found = state.cart.find(product => product.id == item.id)

            if(found){
                found.quantity++
                found.totalPrice = found.quantity * found.price
            }else{
                state.cart.push(item)

                Vue.set(item, 'quantity', 1)
                Vue.set(item, 'totalPrice', parseFloat(item.price))
            }

            state.cartCount++
            
            this.commit('SAVE_CART')
        },
        REMOVE_ITEM_FROM_CART(state, item){
            let index = state.cart.indexOf(item)

            if(index > -1){
                let product = state.cart[index]
                state.cartCount -= product.quantity

                state.cart.splice(index,1)
            }

            this.commit('SAVE_CART')
        },
        DECREMENT_CART_QUANTITY(state, item){
            item.quantity--
            item.totalPrice = item.quantity * item.price

            console.log(`Removed 1 ${item.name} from cart`)

            state.cartCount--

            this.commit('saveCart')
        },
        SAVE_CART(state){
            sessionStorage.setItem('cart', JSON.stringify(state.cart))
            sessionStorage.setItem('cartCount', state.cartCount)
        },
        CLEAR_CART(state){
            state.cart = []
            state.cartCount = 0

            sessionStorage.removeItem('cart')
            sessionStorage.removeItem('cartCount')
        }
    },
    actions: {
        loadUserLogged(context){
            axios.get('api/users/me').then(res => {
                context.commit('SET_USER', res.data)
            })
            .catch(() => {
                context.commit('CLEAR_USER')
            })
        },
        loadUsers(context){
            axios.get('api/users').then(res => {
                context.commit('SET_USER_LIST', res.data.data)
            }).catch(() => {
                context.commit('SET_USER_LIST', null)
            })
        },
        loadProducts(context){
            axios.get('api/products').then(res => {
                context.commit('SET_PRODUCTS_LIST', res.data.data)
            }).catch(() => {
                context.commit('SET_PRODUCTS_LIST', null)
            })
        },
        loadOrders(context){
            axios.get('api/orders/').then(res => {
                context.commit('SET_ORDERS_LIST', res.data.data)
            }).catch(() => {
                context.commit('SET_ORDERS_LIST', null)
            })
        }
    },
})