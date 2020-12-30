<template>
    <v-main>
        <v-container fluid>
            <div class="h1">Delivery Dashboard</div>
            <v-card v-if="currentOrder != ''">
                <v-card-title>Ongoing order</v-card-title>
                <v-card-subtitle>Remember to complete this order once you're done delivering it</v-card-subtitle>
                <v-card-subtitle><strong>Deliver to:</strong></v-card-subtitle>
                <v-row>
                    <v-col>
                        <v-expansion-panels flat>
                            <v-expansion-panel>
                                <v-expansion-panel-header>
                                    <v-row align="center" class="spacer" no-gutters>
                                        <v-col>
                                            <v-avatar size="100" class="ml-4 mb-2">
                                                <v-img :src="'/storage/fotos/' + currentOrder.customer.photo_url"></v-img>
                                            </v-avatar>
                                        </v-col>
                                        <v-col>
                                            <strong v-html="currentOrder.customer.name"></strong>
                                        </v-col>
                                    </v-row>
                                </v-expansion-panel-header>
                                <v-expansion-panel-content>
                                    <v-row>
                                        <v-col>
                                            <strong class="d-block">Address:</strong> {{ currentOrder.customer.address }}
                                        </v-col>
                                        <v-col>
                                            <strong class="d-block">Phone:</strong> {{ currentOrder.customer.phone }}
                                        </v-col>
                                        <v-col>
                                            <strong class="d-block">Email:</strong> {{ currentOrder.customer.email }}
                                        </v-col>
                                    </v-row>
                                    <v-divider></v-divider>
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
                                </v-expansion-panel-content>
                            </v-expansion-panel>
                        </v-expansion-panels>
                    </v-col>
                </v-row>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" class="px-5" @click.prevent="markAsDelivered">Mark as delivered</v-btn>
                </v-card-actions>
            </v-card>
            <v-simple-table>
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
                        <th class="text-right"><v-btn color="primary" @click.prevent="deliver(order)" :disabled="$store.state.isDelivering">Deliver</v-btn></th>
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
        markAsDelivered(){
            this.$toasted.show('Order delivered :D', {type: 'success'})
        },
        deliver(order){
            axios.get('api/orders/' + order.id).then(res => {
                this.currentOrder = res.data.data[0]
                console.log(this.currentOrder)
                console.log(this.currentOrder.items)
                this.$store.state.isDelivering = true
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