<template>
  <v-app>

    <v-app-bar app dark>
      <v-app-bar-nav-icon @click="drawer = true" v-if="$store.state.user && $store.state.user.type === 'EM'"></v-app-bar-nav-icon>
      <v-toolbar-title>Food@Home</v-toolbar-title>

      <v-spacer></v-spacer>
      
      <v-btn to="/" text>Home</v-btn>
      <v-btn to="/products" text>Menu</v-btn>
      <template v-if="$store.state.user">
        <v-btn @click.prevent="logout" text>Logout</v-btn>
      </template>
      <template v-if="!$store.state.user">
        <v-btn to="/login" text>Login</v-btn>
      </template>
      
      <v-spacer></v-spacer>
      
      <v-btn icon to="/cart" v-if="$store.state.user">
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
            <v-list-item-icon><v-icon>mdi-account</v-icon></v-list-item-icon>
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
          <v-list-item @click.prevent="logout">
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
          <v-subheader>MANAGEMENT</v-subheader>
          <v-list-item to="/users">
            <v-list-item-icon>
              <v-icon>mdi-account</v-icon>
            </v-list-item-icon>
            <v-list-item-title>Users</v-list-item-title>
          </v-list-item>
          <v-list-item to="/manager/products">
            <v-list-item-icon>
              <v-icon>mdi-receipt</v-icon>
            </v-list-item-icon>
            <v-list-item-title>Products</v-list-item-title>
          </v-list-item>
          <v-list-item to="/orders">
            <v-list-item-icon>
              <v-icon>mdi-receipt</v-icon>
            </v-list-item-icon>
            <v-list-item-title>Orders</v-list-item-title>
          </v-list-item>
        </v-list-item-group>
        <v-list-item-group>
          <v-subheader>DELIVERY</v-subheader>
          <v-list-item to="/delivery/dashboard">
            <v-list-item-icon>
              <v-icon>mdi-view-dashboard</v-icon>
            </v-list-item-icon>
            <v-list-item-title>Dashboard</v-list-item-title>
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
        this.$store.commit('clearCart')
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