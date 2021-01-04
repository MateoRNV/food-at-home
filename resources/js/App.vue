<template>
  <v-app>
    <v-app-bar app dark>
      <v-toolbar-title>Food@Home</v-toolbar-title>

      <v-spacer></v-spacer>
      
      <v-btn to="/">Home</v-btn>
      <v-btn to="/menu">Menu</v-btn>

      <!-- Manager -->
      <template v-if="$store.state.user && $store.state.user.type === 'EM'">
        <v-btn to="/manager/dashboard">
          Dashboard
        </v-btn>
        <v-btn to="/manager/users">
          Users
        </v-btn>
        <v-btn to="/manager/products">
          Products
        </v-btn>
      </template>

      <!-- Cook -->
      <v-btn to="/cook/dashboard" v-if="$store.state.user && $store.state.user.type === 'EC'">
        Orders Dashboard
      </v-btn>

      <!-- Delivery -->
      <v-btn to="/delivery/dashboard" v-if="$store.state.user && $store.state.user.type === 'ED'">
        Orders Dashboard
      </v-btn>

      <v-spacer></v-spacer>
      
      <v-btn icon to="/cart" v-if="$store.state.user && $store.state.user.type === 'C'">
        <v-icon>mdi-cart-minus</v-icon>
        <v-badge v-if="$store.state.cartCount > 0" :content="$store.state.cartCount"></v-badge>
        
      </v-btn>

      <v-menu offset-y v-if="$store.state.user">
        <template v-slot:activator="{ on, attrs }">
          <v-btn v-bind="attrs" v-on="on">
            <v-icon left>mdi-account</v-icon>
            {{ (($store.state.user.name).split(" "))[0] }}
          </v-btn>
        </template>

        <v-list>
          <v-list-item to="/me/orders" v-if="$store.state.user.type === 'C'">
            <!-- @click.prevent="myself" -->
            <v-list-item-icon><v-icon>mdi-bookmark</v-icon></v-list-item-icon>
            <v-list-item-title>Orders</v-list-item-title>
          </v-list-item>
          <v-list-item to="/me/edit">
            <v-list-item-icon><v-icon>mdi-settings</v-icon></v-list-item-icon>
            <v-list-item-title>Settings</v-list-item-title>
          </v-list-item>
          <v-list-item @click.prevent="logout">
            <v-list-item-icon><v-icon>mdi-logout</v-icon></v-list-item-icon>
            <v-list-item-title>Logout</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
      <v-btn v-else to="/login">
        Login
      </v-btn>
    </v-app-bar>

    <router-view></router-view>
      
  </v-app>
</template>

<script>
// import ProductComponent from "./components/products";
// import LoginComponent from "./components/login";
// import UserComponent from "./components/users"
// import CookComponent from "./components/cook"
// import EditProfileComponent from "./components/editProfile"

export default {
  // components: {
  //   product: ProductComponent,
  //   login: LoginComponent,
  //   user: UserComponent,
  //   cook: CookComponent,
  //   edit: EditProfileComponent,
  // },
  data(){
    return{
      drawer: false,
      group: null,
    }
  },
  methods: {
    logout(){
      axios.post('/api/logout').then(res => {
        this.$socket.emit('update_employee_list', this.$store.state.user.id)

        this.$store.commit('CLEAR_USER')
        this.$store.commit('CLEAR_CART')
        this.$router.push('/login')
      }).catch(error => {
        console.log('invalid request')
      })
    },
    // myself(){
    //   this.$store.dispatch('loadUserLogged')
    //   .then(() => {
    //     if(this.$store.state.user){
    //       this.$toasted.show('User currently logged: ' +
    //         this.$store.state.user.name + '<br>' +
    //         this.$store.state.user.email, {type: 'info'}
    //       )
    //     }else{
    //       this.$toasted.show('No user logged in', {type: 'warning'})
    //     }
    //   })
    // }
  },
  sockets: {
    connect(){
      if(this.$store.state.user){
        this.$socket.emit('user_logged', this.$store.state.user)
      }
    },
    disconnect(){
      this.$socket.emit('update_employee_list', this.$store.state.user)
    },
    user_blocked(){
      this.logout()
      this.$toasted.show('You\'ve been blocked!', {type: 'warning'})
    },
    global_message(payload){
      // To modify
      this.$toasted.show('Attention ' + this.$store.state.user.name + ': ' + payload.message, {type: 'warning'})
    },
    customer_message(payload){
      this.$toasted.show(payload.message, {type: payload.messageType })
    },
    destination_user_not_logged(payload){
      this.$toasted.show(payload.destinationUser.name + ' is not currently online', {type: 'warning'})
    }
  },
};
</script>