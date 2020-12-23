<template>
    <v-main>
        <v-container fluid>
            <div class="h1">Delivery Dashboard</div>
            <v-card>
                <v-card-title>Ongoing order</v-card-title>
                <v-card-subtitle>Remember to complete this order once you're done delivering it</v-card-subtitle>
                <v-card-actions>
                    <v-btn color="primary" @click.prevent="getOrders" text>Complete</v-btn>
                </v-card-actions>
            </v-card>
            <v-data-table
                :headers="headers"
            >
            </v-data-table>
        </v-container>
    </v-main>
</template>

<script>
export default {
    data: {
        headers: [
            {text: 'Order ID'},
        ],
        orders: []
    },
    methods: {
        getOrders(){
            axios.get('api/orders').then(res => {
                this.orders = res.data.data
                console.log(this.orders)
            })
        }
    }
}
</script>

<style>

</style>