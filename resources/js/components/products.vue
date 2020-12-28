<template>
  <v-main class="grey lighten-4">
    <v-container fluid>
      <div class="white rounded-lg px-5 py-5 my-5">
        <div class="text-h2 text-center font-weight-bold d-block mb-5">Our menu</div>
       
        <div class="d-flex flex-wrap justify-space-around">
          <v-data-iterator
              :items="products"
              :items-per-page.sync="itemsPerPage"
              :page="page"
              :search="search"
              :multi-sort="true"
              hide-default-footer
            >
              <!-- Toolbar -->
              <template v-slot:header>
                <v-toolbar
                  class="mb-1"
                  flat
                >
                  <v-text-field
                    v-model="search"
                    label="Search products"
                    clearable
                    flat
                    solo-inverted
                    hide-details
                    prepend-inner-icon="mdi-magnify"
                  >
                  </v-text-field>
                  <template v-if="$vuetify.breakpoint.mdAndUp">
                    <v-spacer></v-spacer>
                    <v-select
                      v-model="sortBy"
                      flat
                      solo-inverted
                      hide-details
                      :items="types"
                      prepend-inner-icon="mdi-magnify"
                      label="Sort by"
                    ></v-select>
                    <v-spacer></v-spacer>
                    <v-btn-toggle
                      v-model="sortDesc"
                      mandatory
                    >
                      <v-btn
                        large
                        depressed
                        color="blue"
                        :value="false"
                      >
                        <v-icon>mdi-arrow-up</v-icon>
                      </v-btn>
                      <v-btn
                        large
                        depressed
                        color="blue"
                        :value="true"
                      >
                        <v-icon>mdi-arrow-down</v-icon>
                      </v-btn>
                    </v-btn-toggle>
                  </template>
                </v-toolbar>
              </template>

              <template v-slot:default="props">  
                <v-row>
                  <v-col
                    v-for="product in props.items"
                    :key="product.id"
                    
                  >
                    <v-card
                      class="my-12"
                      width="300" 
                    >

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
                        <v-btn class="mb-4 white--text" color="grey darken-4" @click.prevent="addToCart(product)" v-if="$store.state.user">
                          <v-icon left class="mr-5">mdi-cart-minus</v-icon>
                          Add to cart
                        </v-btn>
                        <v-btn class="mb-4 white--text" color="grey darken-4" to="/login" v-else>
                          <v-icon left class="mr-5">mdi-cart-minus</v-icon>
                          Add to cart
                        </v-btn>
                      </div>
                    </v-card>
                  </v-col>
                </v-row>
              </template>

              <template v-slot:footer>
                <v-row align="center" justify="center">
                  <span class="grey--text">Items per page</span>
                  <v-menu offset-y>
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn
                        dark
                        text
                        color="primary"
                        class="ml-2"
                        v-bind="attrs"
                        v-on="on"
                      >
                        {{ itemsPerPage }}
                        <v-icon>mdi-chevron-down</v-icon>
                      </v-btn>
                    </template>
                    <v-list>
                      <v-list-item
                        v-for="(number, index) in itemsPerPageArray"
                        :key="index"
                        @click="updateItemsPerPage(number)"
                      >
                        <v-list-item-title>{{ number }}</v-list-item-title>
                      </v-list-item>
                    </v-list>
                  </v-menu>
                  
                  <v-spacer></v-spacer>

                  <span class="mr-4 grey--text">
                    Page {{page}} of {{numberOfPages}}
                  </span>

                  <v-btn
                    fab 
                    dark 
                    color="blue darken-3" 
                    class="mr-1"
                    @click.prevent="formerPage"
                  >
                    <v-icon>mdi-chevron-left</v-icon>
                  </v-btn>
                  <v-btn
                    fab 
                    dark 
                    color="blue darken-3" 
                    class="mr-1"
                    @click.prevent="nextPage"
                  >
                    <v-icon>mdi-chevron-right</v-icon>
                  </v-btn>
                </v-row>
              </template>
          </v-data-iterator>
        </div>
      </div>
    </v-container>
  </v-main>
</template>

<script>
export default {
  data() {
    return {
      search: '',
      itemsPerPageArray: [9, 12, 15],
      page: 1,
      itemsPerPage: 9,
      types: ['hot dish', 'cold dish', 'dessert', 'drink'],
      sortBy: 'name',
      sortDesc: false,
      products: [],
    }
  },
  methods: {
    getProducts(){
      axios.get('api/products').then((response) => {
        this.$root.products = response.data.data;
        this.products = this.$root.products;
        console.log(this.products)
      });
    },
    addToCart(product){
      this.$store.commit('addToCart', product)
      this.$toasted.show(product.name + ' added to cart', {type: 'success', action: { text: 'View cart', push: {path: 'cart'}}})
    },
    nextPage () {
      if (this.page + 1 <= this.numberOfPages) this.page += 1
    },
    formerPage () {
      if (this.page - 1 >= 1) this.page -= 1
    },
    updateItemsPerPage (number) {
      this.itemsPerPage = number
    },
  },
  mounted() {
    this.getProducts()
  },
  computed:{
    numberOfPages () {
      return Math.ceil(this.products.length / this.itemsPerPage)
    },
    filteredKeys () {
      return this.types.filter(key => key !== 'name')
    },
  }
};
</script>
