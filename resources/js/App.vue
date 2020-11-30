<template>
  <v-app>
    <v-app-bar color="deep-purple" app dark dense>
      <v-tabs centered class="m1-n9">
        <v-tab to="/">Home</v-tab>
        <v-tab to="/products">Menu</v-tab>
        <v-tab to="/users">Users</v-tab>
        <v-tab to="/login">Login</v-tab>
      </v-tabs>

      <v-btn icon>
        <v-icon>mdi-cart-minus</v-icon>
        <v-badge content="6"></v-badge>
      </v-btn>

      <v-menu offset-y>
        <template v-slot:activator="{ on, attrs }">
          <v-btn icon v-bind="attrs" v-on="on">
            <v-icon>mdi-account</v-icon>
          </v-btn>
        </template>

        <v-list dense>
          <v-list-item to="/login">
            <v-list-item-icon><v-icon>mdi-bookmark</v-icon></v-list-item-icon>
            <v-list-item-title>user</v-list-item-title>
          </v-list-item>
          <v-list-item to="/login">
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
        console.log('User has logged out')
      }).catch(error => {
        console.log('invalid request')
      })
    }
  },
};
</script>
