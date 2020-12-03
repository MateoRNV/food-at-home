import Axios from 'axios'
import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        user: null,
        users: [],
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
        }
    }
})