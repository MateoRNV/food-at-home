<template>
  <v-row dense>
    <v-col v-for="product in products" :key="product.id">
      <v-card :loading="loading" class="my-12" max-width="300" >
          <template slot="progress">
            <v-progress-linear color="deep-purple" height="10" indeterminate></v-progress-linear>
          </template>

          <v-img height="250" src="https://cdn.vuetifyjs.com/images/cards/cooking.png"></v-img>

          <v-card-title>{{ product.name }}</v-card-title>

          <v-card-text>
            <div class="grey--text ml-4">{{ product.price }}€ • {{ product.type }}</div>
            <div>{{ product.description }}</div>
          </v-card-text>
          
          
          <div class="text-center">
            <v-btn class="mb-4 white--text" color="grey darken-4">
              <v-icon left class="mr-5">mdi-cart-minus</v-icon>
              Add to cart
            </v-btn>
          </div>
          
          
      </v-card>
    </v-col>
    
  </v-row>
</template>

<script>
export default {
  data: () => ({  
    loading: false,
    products: [],
  }),
  mounted() {
    axios.get("api/products").then((response) => {
      this.$root.products = response.data.data;
      this.products = this.$root.products;
    });
  },
};
</script>
