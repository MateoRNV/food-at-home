<template>
  <v-app>
    <v-main>
      <v-container fluid>
        <div class="h1">Cook Dashboard</div>
        <v-card v-if="isNotEmpty" elevation="4">
          <v-card-title> Order {{ order.id }} </v-card-title>
          <v-card-subtitle>Start to preparing at {{ order }}</v-card-subtitle>
          <v-card-actions
            ><v-btn
              color="primary"
              class="px-5"
              @click.prevent="completePreparing"
              >Mark as Prepared</v-btn
            >
          </v-card-actions>
        </v-card>
        <v-card v-else>Waiting for an order to prepare</v-card>
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
      isNotEmpty: null,
    };
  },
  methods: {
    getOrders() {
      // console.log(this.$store.state.user.id);
      axios
        // .get("api/orders/" + this.$store.state.user.id + "/P")
        .get("api/orders/4/P")
        .then((response) => {
          this.orders = response.data.data;
          this.order = this.orders[0];
          console.log(response.data.data[0]);
          if (this.order !== "undefined") {
            this.isNotEmpty = true;
          } else {
            this.isNotEmpty = false;
          }
          console.log(this.isNotEmpty);
        });
    },
    completePreparing() {
      axios
        .post('api/orders/'+this.order.id+'/status/R')
        .then((response) => {
          window.location.reload();
          console.log(response.data)
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