<template>
  <v-app>
    <v-main>
      <v-container fluid>
        <div class="h1">Cook Dashboard</div>
        <!--<v-card  elevation="4">
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
        </v-card>--->

        <v-card v-if="!isEmpty">
          <v-card-title>Your current order</v-card-title>
          <v-card-subtitle
            >Below you'll find information regarding your current order and it's
            customer. Remember to put the order Ready! once you're done
            preparing it</v-card-subtitle
          >
          <v-container fluid>
            <v-row align="start" class="mb-5">
              <v-col
                justify="center"
                v-if="$store.state.currentOrder.customer.photo_url != null"
              >
                <v-avatar size="100" class="ml-4 mb-2">
                  <v-img
                    :src="
                      '/storage/fotos/' +
                      $store.state.currentOrder.customer.photo_url
                    "
                  ></v-img>
                </v-avatar>
              </v-col>
              <v-col>
                <strong class="text-h6">Customer Information</strong>
                <span class="d-block">{{
                  $store.state.currentOrder.customer.name
                }}</span>
                <span class="d-block">{{
                  $store.state.currentOrder.customer.address
                }}</span>
                <span class="d-block">{{
                  $store.state.currentOrder.customer.phone
                }}</span>
                <span class="d-block">{{
                  $store.state.currentOrder.customer.email
                }}</span>
              </v-col>
              <v-col>
                <strong class="text-h6">Order Information</strong>
                <span class="d-block"
                  ><strong>Order ID: </strong>
                  {{ $store.state.currentOrder.id }}</span
                >
                <span class="d-block"
                  ><strong>Started at: </strong>
                  {{ $store.state.currentOrder.current_status_at }}</span
                >
                <span class="d-block"><strong>Time elapsed: </strong> #</span>
              </v-col>
              <v-col>
                <span class="d-block"><strong>Notes:</strong></span>
                {{
                  $store.state.currentOrder.notes != null
                    ? $store.state.currentOrder.notes
                    : "None"
                }}
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
                  <tr
                    v-for="item in $store.state.currentOrder.items"
                    :key="item.id"
                  >
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
                    <td>{{ item.sub_total_price }}€</td>
                  </tr>
                  <tr>
                    <td></td>
                    <td></td>
                    <td>
                      <strong>Total</strong>
                    </td>
                    <td>{{ $store.state.currentOrder.total_price }}€</td>
                  </tr>
                </tbody>
              </v-simple-table>
            </v-row>
          </v-container>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="primary"
              class="px-5"
              @click.prevent="completePreparing()"
              >Mark as Ready</v-btn
            >
          </v-card-actions>
        </v-card>
        <v-card v-else>
          <v-card-title> Waiting for an order to prepare </v-card-title></v-card
        >

        <v-data-table
          :headers="headers"
          :items="ordersHolding"
          :search="search"
          show-group-by
          multi-sort
          class="elevation-1"
        >
          <template v-slot:item.actions="{ item }">
            <v-btn
              color="primary"
              dark
              class="mb-2"
              @click="takeOrder(item)"
            >
              Take Order
            </v-btn>
          </template>
        </v-data-table>
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
      search: "",
      isEmpty: null,
      ordersHolding: [],
      headers: [
        { text: "Id", value: "id", groupable: false },
        { text: "Date", value: "date", groupable: false },
        { text: "Price", value: "total_price", groupable: false },
        { text: "Status", value: "status", groupable: false },
        { text: "Client", value: "customer.id", groupable: false },
        {
          text: "Actions",
          value: "actions",
          groupable: false,
          sortable: false,
        },
      ],
    };
  },
  methods: {
    getOrder() {
      axios
        .get("api/orders/" + this.$store.state.user.id + "/status/P")
        .then((response) => {
          this.orders = response.data.data;
          this.order = this.orders[0];
          if (this.orders.length == 0) {
            this.isEmpty = true;
            this.$store.commit("CLEAR_CURRENT_ORDER", this.order);
          } else {
            this.isEmpty = false;
            this.$store.commit("SET_CURRENT_ORDER", this.order);
          }
          console.log("boolean " + this.isEmpty);
        });
    },
    getOrdersStatus() {
      axios.get("api/orders/status/H").then((response) => {
        this.ordersHolding = response.data;

        console.log(response);
      });
    },
    completePreparing() {
      axios
        .post("api/orders/" + this.order.id + "/status/R")
        .then((response) => {
          this.getOrder();
          console.log(response.data);
      });
    },
    takeOrder(item){
      axios
        .post("api/orders/" + item.id + "/status/P")
        .then((response) => {
          this.getOrder();
          console.log(response.data);
      });
    }
  },
  mounted() {
    this.getOrder();
    this.getOrdersStatus();
  },
};
</script>

<style>
</style>