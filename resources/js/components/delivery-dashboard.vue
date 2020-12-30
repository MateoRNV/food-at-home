<template>
    <v-main>
        <v-container fluid>
            <div class="h1">Delivery Dashboard</div>
            <v-card v-if="currentOrder != ''">
                <v-card-title>Your current order</v-card-title>
                <v-card-subtitle>Below you'll find information regarding your current order and it's customer. Remember to complete this order once you're done delivering it</v-card-subtitle>
                <v-container fluid>

                    <v-row align="start" class="mb-5">
                        <v-col justify="center" v-if="currentOrder.customer.photo_url != null">
                            <v-avatar size="100" class="ml-4 mb-2">
                                <v-img :src="'/storage/fotos/' + currentOrder.customer.photo_url"></v-img>
                            </v-avatar>
                        </v-col>
                        <v-col>
                            <strong class="text-h6">Customer Information</strong>
                            <span class="d-block">{{ currentOrder.customer.name }}</span>
                            <span class="d-block">{{ currentOrder.customer.address }}</span>
                            <span class="d-block">{{ currentOrder.customer.phone }}</span>
                            <span class="d-block">{{ currentOrder.customer.email }}</span>
                        </v-col>
                        <v-col>
                            <strong class="text-h6">Order Information</strong>
                            <span class="d-block"><strong>Order ID: </strong> {{ currentOrder.id }}</span>
                            <span class="d-block"><strong>Started at: </strong> {{ currentOrder.current_status_at }}</span>
                            <span class="d-block"><strong>Time elapsed: </strong> #</span>
                        </v-col>
                        <v-col>
                            <span class="d-block"><strong>Notes:</strong></span>
                            {{ currentOrder.notes != null ? currentOrder.notes : 'None' }}
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
                                <tr v-for="item in currentOrder.items" :key="item.id">
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
                                        {{ currentOrder.total_price }}€
                                    </td>
                                </tr>
                            </tbody>
                        </v-simple-table>
                    </v-row>
                </v-container>
                
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" class="px-5" @click.prevent="markAsDelivered">Mark as delivered</v-btn>
                </v-card-actions>
            </v-card>
            <v-simple-table v-if="!currentOrder">
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
                        <th>{{ order.customer_name }}</th>
                        <th>{{ order.current_status_at }}</th>
                        <th class="text-right"><v-btn color="primary" @click.prevent="startDelivery(order)" :disabled="$store.state.isDelivering">Deliver</v-btn></th>
                    </tr>
                </tbody>
            </v-simple-table>
        </v-container>
    </v-main>
</template>

<script>
export default {
    data(){
        return {
            orders:[],
            currentOrder: ''
        }
    },
    methods: {
        getOrders(){
            axios.get('api/orders/status/R').then(res => {
                this.orders = res.data
            })
        },
        markAsDelivered(order){
            axios.post('api/orders/'+order.id+'/status/D')
            this.$toasted.show('Order delivered :D', {type: 'success'})
        },
        startDelivery(order){
            axios.get('api/orders/' + order.id).then(res => {
                this.currentOrder = res.data.data[0]
                this.$store.state.isDelivering = true

                axios.post('api/orders/'+order.id+'/status/T').then(() => {
                    this.$toasted.show('Order in transit', {type: 'success'})
                })
                .catch(e => {
                    this.$toasted.show('There was a problem with the order', {type: 'error'})
                })
            })
        }
    },
    mounted(){
        this.getOrders()
    }
}
</script>

<style>

</style>