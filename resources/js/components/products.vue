<template>
  <div class="white rounded-lg px-5 py-5 my-5">
    <div class="text-h2 text-center font-weight-bold">Our menu</div>
    <div class="d-flex flex-wrap justify-space-around products">
      <v-card
        :loading="loading"
        class="my-12"
        max-width="300"
        v-for="product in products"
        :key="product.id"
      >
        <template slot="progress">
          <v-progress-linear
            color="deep-purple"
            height="10"
            indeterminate
          ></v-progress-linear>
        </template>

        <v-img height="250" :src="`/storage/products/${product.photo_url}`">
          <template v-slot:placeholder>
            <v-row class="fill-height ma-0" align="center" justify="center">
              <v-progress-circular
                indeterminate
                color="grey lighten-5"
              ></v-progress-circular>
            </v-row>
          </template>
        </v-img>

        <v-card-title>{{ product.name }}</v-card-title>

        <v-card-text>
          <div class="grey--text ml-4">
            {{ product.price }}€ • {{ product.type }}
          </div>
          <div>{{ product.description }}</div>
        </v-card-text>

        <div class="text-center">
          <v-btn class="mb-4 white--text" color="grey darken-4">
            <v-icon left class="mr-5">mdi-cart-minus</v-icon>
            Add to cart
          </v-btn>
        </div>
      </v-card>
    </div>
  </div>
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
