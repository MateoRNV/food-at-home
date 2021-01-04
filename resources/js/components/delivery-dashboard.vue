<template>
    <v-main>
        <v-container fluid>
            <div class="h1">Delivery Dashboard</div>
            <v-btn color="red" @click.prevent="$store.commit('CLEAR_CURRENT_ORDER')">
                CLEAN
            </v-btn>
            <v-card v-if="$store.state.currentOrder !== ''">
                <v-card-title>Your current order</v-card-title>
                <v-card-subtitle>Below you'll find information regarding your current order and it's customer. Remember to complete this order once you're done delivering it</v-card-subtitle>
                <v-container fluid>

                    <v-row align="start" class="mb-5">
                        <v-col justify="center" v-if="$store.state.currentOrder.customer.photo_url != null">
                            <v-avatar size="100" class="ml-4 mb-2">
                                <v-img :src="'/storage/fotos/' + $store.state.currentOrder.customer.photo_url"></v-img>
                            </v-avatar>
                        </v-col>
                        <v-col>
                            <strong class="text-h6">Customer Information</strong>
                            <span class="d-block">{{ $store.state.currentOrder.customer.name }}</span>
                            <span class="d-block">{{ $store.state.currentOrder.customer.address }}</span>
                            <span class="d-block">{{ $store.state.currentOrder.customer.phone }}</span>
                            <span class="d-block">{{ $store.state.currentOrder.customer.email }}</span>
                        </v-col>
                        <v-col>
                            <strong class="text-h6">Order Information</strong>
                            <span class="d-block"><strong>Order ID: </strong> {{ $store.state.currentOrder.id }}</span>
                            <span class="d-block"><strong>Started at: </strong> {{ new Date($store.state.currentOrder.current_status_at).toLocaleTimeString() }}</span>
                            <!-- <span class="d-block"><strong>Time elapsed: </strong> {{ currentOrderElapsed }}</span> -->
                        </v-col>
                        <v-col>
                            <span class="d-block"><strong>Notes:</strong></span>
                            {{ $store.state.currentOrder.notes != null ? $store.state.currentOrder.notes : 'None' }}
                        </v-col>
                    </v-row>

                    <v-row>
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
                                <tr v-for="item in $store.state.currentOrder.items" :key="item.id">
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
                                        {{ $store.state.currentOrder.total_price }}€
                                    </td>
                                </tr>
                            </tbody>
                        </v-simple-table>
                    </v-row>
                </v-container>
                
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" class="px-5" @click.prevent="markAsDelivered($store.state.currentOrder)">Mark as delivered</v-btn>
                </v-card-actions>
            </v-card>
            <v-simple-table v-if="!$store.state.currentOrder && orders.length !== 0">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Ready at</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="order in orders" :key="order.id">
                        <th>{{ order.id }}</th>
                        <th>{{ order.customer.name }}</th>
                        <th>{{ new Date(order.current_status_at).toLocaleTimeString() }}</th>
                        <th class="text-right"><v-btn color="primary" @click.prevent="startDelivery(order)">Deliver</v-btn></th>
                    </tr>
                </tbody>
            </v-simple-table>
            <div class="text-h6 text-center" v-if="orders.length === 0 && !$store.state.currentOrder">No orders right now</div>
        </v-container>
    </v-main>
</template>

<script>
export default {
    data(){
        return {
            orders:[],
        }
    },
    methods: {
        getOrders(){
            axios.get('api/orders/status/R').then(res => {
                this.orders = res.data
            })
        },
        markAsDelivered(order){
            axios.post('api/orders/'+order.id+'/status/D').then(res => {
                this.$store.commit('CLEAR_CURRENT_ORDER')

                this.$socket.emit('remove_order_from_list', res.data.order)
                this.$socket.emit('update_orders_list', res.data.order)
                this.$socket.emit('update_employee_list', res.data.order.delivered_by)
                
                this.$toasted.show('Order delivered', {type: 'success'})
            }).catch(() => {
                this.$toasted.show('There was a problem processing your request', {type: 'error'})
            })
        },
        startDelivery(order){
            axios.get('api/orders/' + order.id).then(res => {
                this.$store.commit('SET_CURRENT_ORDER', res.data.data[0])

                axios.post('api/orders/'+order.id+'/status/T').then(res => {
                    this.removeOrderFromList(res.data.order)

                    this.$socket.emit('update_orders_list', res.data.order)
                    this.$socket.emit('update_employee_list', res.data.order.delivered_by)
                    this.$toasted.show('Order in transit', {type: 'success'})

                })
                .catch(e => {
                    this.$toasted.show('There was a problem with the order', {type: 'error'})
                })
            })
        },
        removeOrderFromList(order){
            const index = this.orders.findIndex(v => v.id === order.id)

            if(index > -1){
                this.orders.splice(index, 1)
            }
        }
    },
    mounted(){
        this.getOrders()
    },
    sockets: {
        delivery_dashboard_update(){
            this.$store.commit('CLEAR_CURRENT_ORDER')
        },
        update_orders_list(orderToRemove){
            this.removeOrderFromList(orderToRemove)
        }
    }
}
</script>

<style>

</style>