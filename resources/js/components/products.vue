<template>
  <v-main class="grey lighten-4">
    <v-container fluid>
      <div class="white rounded-lg px-5 py-5 my-5">
        <div class="text-h2 text-center font-weight-bold">Our menu</div>
        
        <v-row align="center" justify="center">
          <v-col>
            <v-text-field label="Search" v-model="search"></v-text-field>
          </v-col>
          <v-col>
            <v-btn-toggle tile group>
              <v-btn value="€">€</v-btn>
              <v-btn value="€€">€€</v-btn>
              <v-btn value="€€€">€€€</v-btn>
            </v-btn-toggle>
          </v-col>
          <v-col>
            <v-select label="Type" :items="types" v-model="type_sort" outlined dense>
            </v-select>
          </v-col>
        </v-row>
        
        <div class="d-flex flex-wrap justify-space-around products">
          <v-card
            class="my-12"
            max-width="300"
            v-for="product in filteredList"
            :key="product.id"
            :search="search"
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
              <v-btn class="mb-4 white--text" color="grey darken-4" @click.prevent="addToCart(product)">
                <v-icon left class="mr-5">mdi-cart-minus</v-icon>
                Add to cart
              </v-btn>
            </div>
          </v-card>
        </div>
        <div class="text-center">
          <v-pagination v-model="pagination.currentPage" :length="pagination.totalPages" @input="handlePagination"></v-pagination>
        </div>
      </div>
    </v-container>
  </v-main>
</template>

<script>
export default {
  data() {
    return {
      products: [],
      search: '',
      types: ['hot dish', 'cold dish', 'dessert', 'drink'],
      type_sort: 'drink',
      pagination: {
        currentPage: 1,
        previousPage: null,
        nextPage: null,
        totalPages: 0
      },
    }
  },
  methods: {
    getProducts(){
      axios.get('api/products?page=' + this.pagination.currentPage).then((response) => {
        this.$root.products = response.data.data;
        this.products = this.$root.products;
        // Pagination
        this.pagination.currentPage = response.data.meta.current_page;
        this.pagination.totalPages = response.data.meta.last_page;
      });
    },
    handlePagination(){
      this.getProducts();
    },
    orderedItems(items){ 
      console.log(name)
      return items.filter(items => {
        if(item[this.type_sort]) return item;
      })
    },
    addToCart(product){
      this.$store.commit('addToCart', product)
      this.$toasted.show(product.name + ' added to cart', {type: 'success', action: { text: 'View cart', push : {path: 'cart'}}})
    }
  },
  mounted() {
    this.getProducts();
  },
  computed:{
    filteredList(){
      return this.products.filter(product => {
        return product.name.toLowerCase().includes(this.search.toLowerCase()) || product.type.toLowerCase().includes(this.search.toLowerCase())
      })
    }
  }
};
</script>
