import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

let cart = window.sessionStorage.getItem('cart');
let cartCount = window.sessionStorage.getItem('cartCount');

export default new Vuex.Store({
    state: {
        user: null,
        users: [],
        cart: cart ? JSON.parse(cart) : [],
        cartCount: cartCount ? parseInt(cartCount) : 0,

        isDelivering: false,
    },
    mutations: {
        clearUser (state){
            state.user = null
        },
        setUser (state, user){
            state.user = user
        },
        setUsers(state, users){
            state.users = users
        },
        addToCart(state, item){
            console.log(`${item.name} added to cart`)

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
            
            this.commit('saveCart')
        },
        removeFromCart(state, item){
            let index = state.cart.indexOf(item)

            if(index > -1){
                let product = state.cart[index]
                state.cartCount -= product.quantity

                state.cart.splice(index,1)
            }

            this.commit('saveCart')
        },
        decrementCart(state, item){
            item.quantity--
            item.totalPrice = item.quantity * item.price

            console.log(`Removed 1 ${item.name} from cart`)

            state.cartCount--

            this.commit('saveCart')
        },
        saveCart(state){
            sessionStorage.setItem('cart', JSON.stringify(state.cart))
            sessionStorage.setItem('cartCount', state.cartCount)
        },
        clearCart(state){
            state.cart = []
            state.cartCount = 0

            sessionStorage.removeItem('cart')
            sessionStorage.removeItem('cartCount')
        }
    },
    actions: {
        loadUserLogged(context){
            axios.get('api/users/me').then(res => {
                context.commit('setUser', res.data)
            })
            .catch(e => {
                context.commit('clearUser')
            })
        },
        loadUsers(context){
            axios.get('api/users').then(res => {
                context.commit('setUsers', res.data.data)
            }).catch(e => {
                context.commit('setUsers', null)
            })
        },
    }
})