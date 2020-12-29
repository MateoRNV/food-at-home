<template>
  <v-app>
    <v-main>
      <v-container fluid>
        <div class="h1">Cook Dashboard</div>
        <v-card elevation="4">
          <v-card-title> Order {{ order.id }} </v-card-title>
          <v-card-subtitle>Start to preparing at {{ order }}</v-card-subtitle>
          <v-card-actions
            ><v-btn
              color="primary"
              class="px-5"
              @click.prevent=""
              >Mark as Prepared</v-btn
            >
          </v-card-actions>
        </v-card>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
export default {
  data: function () {
    return {
      orders: [],
      order: null,
    };
  },
  methods: {
    getOrders() {
      // console.log(this.$store.state.user.id);
      axios
        .get("api/orders/" + this.$store.state.user.id + "/P")
        // .get("api/orders/4/P")
        .then((response) => {
          this.orders = response.data.data;
          this.order = this.orders[0];
          console.log(this.order.id);
        });
    },
  },
  mounted() {
    this.getOrders();
  },
};
</script>

<style>
</style>