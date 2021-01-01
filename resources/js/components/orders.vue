<template>
  <v-main class="grey lighten-4">
      <v-container fluid>
          <div class="white rounded-lg px-5 py-5 my-5 text-center">
            <div class="headline mb-5">My orders</div>
            <template v-if="orders === undefined || orders.length == 0">
                <div class="text-h6 py-5">You haven't made any orders yet</div>
                <v-btn to="/products" color="primary">Order now</v-btn>
            </template>
            <v-expansion-panels v-else flat>
                <v-expansion-panel
                    v-for="order in orders" :key="order.id"
                >
                    <v-expansion-panel-header>Order #{{ order.id }}</v-expansion-panel-header>
                    <v-expansion-panel-content>
                        <v-simple-table class="w-100">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in order.items" :key="item.id">
                                    <td>
                                        <v-img
                                        :src="`/storage/products/${item.product_photo}`"
                                        height="100"
                                        width="100"
                                        ></v-img>
                                    </td>
                                    <td>
                                        {{ item.product_name }}
                                    </td>
                                    <td>
                                        {{ item.quantity }}
                                    </td>
                                    <td>
                                        {{ item.sub_total_price }}€
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <strong>Total</strong>
                                    </td>
                                    <td>
                                        {{ order.total_price }}€
                                    </td>
                                </tr>
                            </tbody>
                        </v-simple-table>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </v-expansion-panels>
            
          </div>
      </v-container>
  </v-main>
</template>

<script>
export default {
    data(){
        return {
            orders: [],
            user: this.$store.state.user
        }
    },
    methods : {
        getCustomerOrders(){
            axios.get('api/users/me').then(user => {
                axios.get('api/customers/'+ user.data.id +'/orders').then(res => {
                    this.orders = res.data.orders
                })
                .catch(e => {
                    this.$toasted.show('There was a problem while fetching your orders.', {type: 'error'} )
                })
            })
        }
    },
    mounted(){
        this.getCustomerOrders()
    }
}
</script>

<style>

</style>