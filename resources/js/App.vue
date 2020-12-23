<template>
  <v-app>

    <v-app-bar app dark dense>
      <v-app-bar-nav-icon @click="drawer = true"></v-app-bar-nav-icon>
      <v-toolbar-title>Food@Home</v-toolbar-title>

      <v-spacer></v-spacer>
      
      <v-btn to="/">Home</v-btn>
      <v-btn to="/products">Menu</v-btn>
      <template v-if="$store.state.user">
        <v-btn to="/users">Users</v-btn>
        <v-btn @click.prevent="logout">Logout</v-btn>
      </template>
      <template v-if="!$store.state.user">
        <v-btn to="/login">Login</v-btn>
      </template>
      
      <v-spacer></v-spacer>
      



      <!-- <v-tabs centered class="m1-n9">
        <v-tab to="/">Home</v-tab>
        <v-tab to="/products">Menu</v-tab>
        <template v-if="$store.state.user">
          <v-tab to="/users">Users</v-tab>
          <v-tab @click.prevent="logout">Logout</v-tab>
        </template>
        <template v-if="!$store.state.user">
          <v-tab to="/login">Login</v-tab>
        </template>
      </v-tabs> -->

      <v-btn icon to="/cart">
        <v-icon>mdi-cart-minus</v-icon>
        <v-badge v-if="$store.state.cartCount > 0" :content="$store.state.cartCount"></v-badge>
        
      </v-btn>

      <v-menu offset-y>
        <template v-slot:activator="{ on, attrs }">
          <v-btn icon v-bind="attrs" v-on="on">
            <v-icon>mdi-account</v-icon>
          </v-btn>
        </template>

        <v-list>
          <v-list-item to="/login" v-if="$store.state.user && $store.state.user.type === 'EM'">
            <v-list-item-icon><v-icon>mdi-bookmark</v-icon></v-list-item-icon>
            <v-list-item-title>{{$store.state.user.name}}</v-list-item-title>
          </v-list-item>
          <v-list-item @click.prevent="myself">
            <v-list-item-icon><v-icon>mdi-bookmark</v-icon></v-list-item-icon>
            <v-list-item-title>Orders</v-list-item-title>
          </v-list-item>
          <v-list-item to="/login">
            <v-list-item-icon><v-icon>mdi-settings</v-icon></v-list-item-icon>
            <v-list-item-title>Settings</v-list-item-title>
          </v-list-item>
          <v-list-item to="/login">
            <v-list-item-icon><v-icon>mdi-logout</v-icon></v-list-item-icon>
            <v-list-item-title>Logout</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-app-bar>

    <v-navigation-drawer
      v-model="drawer"
      absolute
      temporary
    >
      <v-list nav dense>
        <v-list-item-group>
          <v-list-item>
            <v-list-item-icon>
              <v-icon>mdi-home</v-icon>
            </v-list-item-icon>
            <v-list-item-title>Home</v-list-item-title>
          </v-list-item>
        </v-list-item-group>
      </v-list>
    </v-navigation-drawer>

    
    <router-view></router-view>
      
  </v-app>
</template>

<script>
import ProductComponent from "./components/products";
import LoginComponent from "./components/login";
import UserComponent from "./components/users"

export default {
  components: {
    product: ProductComponent,
    login: LoginComponent,
    user: UserComponent
  },
  data(){
    return{
      drawer: false,
      group: null,
    }
  },
  methods: {
    logout(){
      axios.post('/api/logout').then(res => {
        this.$toasted.show('User has logget out', {type: 'warning'})
        this.$store.commit('clearUser')
        this.$router.push('/login')
      }).catch(error => {
        console.log('invalid request')
      })
    },
    myself(){
      this.$store.dispatch('loadUserLogged')
      .then(() => {
        if(this.$store.state.user){
          this.$toasted.show('User currently logged: ' +
            this.$store.state.user.name + '<br>' +
            this.$store.state.user.email, {type: 'info'}
          )
          console.log(this.$store.state.user)
        }else{
          this.$toasted.show('No user logged in', {type: 'warning'})
        }
      })
    }
  },
};
</script>
