<template>
  <v-main>
      <v-container fluid>
          <div class="px-5 py-5 my-5 text-center">
            <div class="text-h4 text-center my-5">My Orders</div>
            <template v-if="orders === undefined || orders.length == 0">
                <div class="text-h6 py-5">You haven't made any orders yet</div>
                <v-btn to="/menu" color="primary">Order now</v-btn>
            </template>
            <v-expansion-panels v-else flat class="mt-12">
                <v-expansion-panel
                    v-for="order in orders" :key="order.id"
                >
                    <v-expansion-panel-header>
                        <v-row>
                            <v-col>
                                Order #{{ order.id }}
                            </v-col>
                            <v-col>
                                <v-chip :color="statusColor(order.status)" class="px-5">
                                    {{ toString(order.status) }}
                                </v-chip>
                            </v-col>
                        </v-row>
                    </v-expansion-panel-header>
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
                                <tr v-if="order.notes != null">
                                    <td></td>
                                    <td></td>
                                    <td><strong>Notes:</strong></td>
                                    <td>{{ order.notes }}</td>
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
        }
    },
    methods : {
        getCustomerOrders(){
            axios.get('api/user/me').then(user => {
                axios.get('api/customers/'+ user.data.id +'/orders').then(res => {
                    this.orders = res.data.orders
                })
                .catch(e => {
                    this.$toasted.show('There was a problem while fetching your orders.', {type: 'error'} )
                })
            })
        },
        toString(status){
            switch(status){
                case 'H':
                    return 'Holding'
                case 'P':
                    return 'Preparing'
                case 'R':
                    return 'Ready'
                case 'T':
                    return 'In Transit'
                case 'D':
                    return 'Delivered'
                case 'C':
                    return 'Cancelled'
            }
        },
        statusColor(status){
            switch(status){
                case 'H':
                    return 'grey text-light'
                case 'P':
                    return 'yellow lighten-1'
                case 'R':
                    return 'green text-light'
                case 'T':
                    return 'deep-purple darken-1 text-light'
                case 'D':
                    return 'green darken-3 text-light'
                case 'C':
                    return 'red text-light'
            }
        },
        refreshOrder(order){
            let index = this.orders.findIndex(item => item.id === order.id)
            console.log(order)
            if(index > -1){
                this.orders.splice(index, 1, order)
            }
        }
    },
    mounted(){
        this.getCustomerOrders()
    },
    sockets: {
        update_orders_list(orderToUpdate){
            this.refreshOrder(orderToUpdate)
        }
    }
}
</script>

<style>

</style>