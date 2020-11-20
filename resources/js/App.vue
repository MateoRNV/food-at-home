<template>
  <v-app>
    <v-app-bar color="deep-purple" app dark dense>
      <v-tabs centered class="m1-n9">
        <v-tab to="/">Home</v-tab>
        <v-tab to="/products">Menu</v-tab>
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
      <!-- <v-avatar
        class="hidden-sm-and-down"
        color="grey darken-1 shrink"
        size="32"
      ></v-avatar> -->
    </v-app-bar>

    <v-main class="grey lighten-4">
      <v-container fluid>
        <router-view></router-view>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import ProductComponent from "./components/products";
import LoginComponent from "./components/login";
export default {
  components: {
    product: ProductComponent,
    login: LoginComponent,
  },
  data(){
    return{
      drawer: false,
      group: null,
    }
  },
  mounted(){
    // axios.get('api/user').then((res) => {
    //   this.user = res.data
    //   console.log(this.user)
    // }).catch((e) => {
    //   console.log(e.data)
    // })
  }
};
</script>