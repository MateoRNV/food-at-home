<template>
  <v-main class="grey lighten-4">
      <v-container fluid>
          <div class="white rounded-lg px-5 py-5 my-5 text-center">
            <div class="headline">My orders</div>
            <template v-if="customerOrders === undefined || customerOrders.length == 0">
                <div class="text-h6 py-5">You haven't made any orders yet</div>
                <v-btn to="/products" color="primary">Order now</v-btn>
            </template>
            <v-expansion-panels v-else>
                <v-expansion-panel
                    v-for="order in customerOrders" :key="order.id"
                >
                    <v-expansion-panel-header>Order #{{ order.id }}</v-expansion-panel-header>
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
            
        }
    },
    methods : {
        getCustomerOrders(){
            
        }
    },
}
</script>

<style>

</style>