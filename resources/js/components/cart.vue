<template>
  <v-main>
    <v-container fluid class="mt-5">
      <v-dialog v-model="dialog" width="500">
        <v-card>
          <div class="container-fluid">
            <v-card-title> Checkout </v-card-title>

            <v-spacer></v-spacer>

            <v-card-text>
              <v-text-field
                v-model="form.notes"
                label="Add a note to your order (optional)"
                required
              >
              </v-text-field>
            </v-card-text>
            <div class="d-flex mx-4 flex-wrap flex-row justify-space-between">
              <div class="font-weight-bold">Price to pay</div>

              <div class="font-weight-bold float-right">
                {{ totalPrice }}€
              </div>
            </div>
            <br />

            <v-divider></v-divider>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="error" @click="dialog = false" class="mr-4"
                >Cancel</v-btn
              >
              <v-btn color="primary" @click="createOrder"> Finish </v-btn>
            </v-card-actions>
          </div>
        </v-card>
      </v-dialog>

      <div v-if="$store.state.cartCount === 0" class="text-center">
        <div class="text-h1 mb-5">:(</div>
        <div class="h1">Your cart is empty</div>
        <v-btn to="/products" color="primary">Order now</v-btn>
      </div>
      <div v-else>
        <div class="h1 text-center">Your order summary</div>
        <v-simple-table>
          <tbody>
            <tr v-for="item in $store.state.cart" :key="item.id">
              <td>
                <v-img
                  :src="`/storage/products/${item.photo_url}`"
                  height="150"
                  width="150"
                ></v-img>
              </td>
              <td>
                <span class="d-block"
                  ><h5>{{ item.name }}</h5></span
                >
                <span class="d-block"
                  ><strong>Type:</strong> {{ item.type }}</span
                >
                <span><strong>Product ID:</strong> {{ item.id }}</span>
              </td>
              <td>
                <v-text-field
                  append-outer-icon="mdi-plus"
                  @click:append-outer="incrementProductQuantity(item)"
                  prepend-icon="mdi-minus"
                  @click:prepend="decrementProductQuantity(item)"
                  type="number"
                  :value="item.quantity"
                  class="text-center"
                  style="width: 100px"
                  readonly
                >
                </v-text-field>
              </td>
              <td>{{ item.price }}€</td>
              <td>{{ (item.price * item.quantity).toFixed(2) }}€</td>
              <td>
                <v-btn @click.prevent="removeFromCart(item)" icon
                  ><v-icon>mdi-close</v-icon></v-btn
                >
              </td>
            </tr>
          </tbody>
        </v-simple-table>
        <v-row>
          <v-col>
            <div class="text-h6 text-left font-weight-bold">
              Order total: {{ totalPrice }}€
            </div>
          </v-col>
          <v-col class="text-right">
            <v-btn color="error" @click.prevent="$store.commit('CLEAR_CART')"
              >Clear cart</v-btn
            >
            <v-btn @click="openDialog" color="primary">Checkout</v-btn>
          </v-col>
        </v-row>
      </div>
    </v-container>
  </v-main>
</template>

<script>
export default {
  data: function () {
    return {
      dialog: false,
      order: "",
      form: {},
    };
  },

  methods: {
    removeFromCart(item) {
      this.$store.commit("REMOVE_ITEM_FROM_CART", item);
    },
    incrementProductQuantity(item) {
      this.$store.commit("ADD_ITEM_TO_CART", item);
    },
    decrementProductQuantity(item) {
      if (item.quantity === 1) {
        return;
      }

      this.$store.commit("DECREMENT_CART_QUANTITY", item);
    },
    openDialog() {
      this.dialog = true;
    },

    createOrder() {
      this.form.customer_id = this.$store.state.user.id;
      this.form.total_price = this.totalPrice;
      this.form.products = this.$store.state.cart;
      axios
        .post("/api/orders/", this.form)
        .then((res) => {
          console.log(res)
          this.$socket.emit('add_order_to_list', res.data.order)

          this.$toasted.show('Order #'+res.data.order.id+' processed successfully', {type: 'success'})
          this.$router.push('/me/orders')

          this.$store.commit('CLEAR_CART')
        })
        .catch((e) => {
          this.$toasted.show('There was a problem while processing your order', {type: 'error'})
          console.log(e);
        });
    },
  },
  computed: {
    totalPrice() {
      let total = 0.0;

      for (let item of this.$store.state.cart) {
        total += item.totalPrice;
      }
      return total.toFixed(2);
    },
  },
};
</script>

<style>
.theme--light.v-data-table
  > .v-data-table__wrapper
  > table
  > tbody
  > tr:hover:not(.v-data-table__expanded__content):not(.v-data-table__empty-wrapper) {
  background: transparent;
}
</style>