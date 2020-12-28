<template>
    <v-main>
        <v-container fluid>
            <div class="h1">Delivery Dashboard</div>
            <v-card>
                <v-card-title>Ongoing order</v-card-title>
                <v-card-subtitle>Remember to complete this order once you're done delivering it</v-card-subtitle>
                <v-card-actions>
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
                        <th class="text-right"><v-btn color="primary" :disabled="$store.state.isDelivering">Deliver</v-btn></th>
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
            currentOrder: {}
        }
    },
    methods: {
        getOrders(){
            axios.get('api/orders/status/R').then(res => {
                this.orders = res.data.data
                console.log(this.orders)
            })
        },
        markAsDelivered(){
            this.$toasted.show('Order delivered :D', {type: 'success'})
            console.log(this.$store.state.user)
        },
        deliver(order){
            this.currentOrder = order
            // this.$store.state.
        }
    },
    mounted(){
        this.getOrders()
    }
}
</script>

<style>

</style>