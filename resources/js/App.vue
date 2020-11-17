<template>
  <v-app>
    <v-system-bar dark color="green darken-1" class="justify-center p-4 white--text">
      <div class="text-h6">
        Your order is on the way
      </div>
    </v-system-bar>

    <!-- <v-navigation-drawer v-model="drawer" absolute temporary>
      <v-list nav dense>
        <v-list-item-group v-model="group" active-class="deep-purple--text text--accent-4">
          <router-link to="/">
            <v-list-item>
              <v-list-item-icon>
                <v-icon>mdi-home</v-icon>
              </v-list-item-icon>
              <v-list-item-title>Home</v-list-item-title>
            </v-list-item>
          </router-link>
          <router-link to="/login">
            <v-list-item>
              <v-list-item-icon>
                <v-icon>mdi-login</v-icon>
              </v-list-item-icon>
              <v-list-item-title>
                Login
              </v-list-item-title>
            </v-list-item>
          </router-link>
        </v-list-item-group>
      </v-list>
    </v-navigation-drawer> -->

    <v-app-bar color="deep-purple" dark dense>
      <!-- <v-app-bar-nav-icon @click="drawer = true"></v-app-bar-nav-icon>
      <v-toolbar-title>Food@Home</v-toolbar-title> -->
      <v-tabs centered class="m1-n9">
        <v-tab to="/">Home</v-tab>
        <v-tab to="/login">Login</v-tab>
      </v-tabs>
    </v-app-bar>
    
    <v-main>
      <v-container fluid>
        <router-view></router-view>
      </v-container>
    </v-main>

    <v-footer app></v-footer>
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
  data: () => ({
    drawer: false,
    group: null,
  })
};
</script>