<template>
  <v-main>
    <v-container fluid>
      <div class="text-h4 text-center my-5">Cook Dashboard</div>

      <v-card v-if="$store.state.currentOrder !== ''">
        <v-card-title>Your current order</v-card-title>
        <v-card-subtitle
          >Below you'll find information regarding your current order and it's
          customer. Remember to mark this order once you're
          done</v-card-subtitle
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
            <v-simple-table class="w-100" v-if="!$store.state.currentOrder && orders.length !== 0">
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
            <div class="text-h6 text-center" v-if="orders.length === 0 && !$store.state.currentOrder">No orders right now</div>
          </v-row>
        </v-container>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="primary"
            class="px-5"
            @click.prevent="completePreparing($store.state.currentOrder)"
            >Mark as Ready</v-btn
          >
        </v-card-actions>
      </v-card>

      <v-data-table
        :headers="headers"
        :items="orders"
        :search="search"
        show-group-by
        multi-sort
      >
        <template v-slot:item.actions="{ item }">
          <v-btn color="primary" dark @click="takeOrder(item)"> Prepare </v-btn>
        </template>
      </v-data-table>
    </v-container>
  </v-main>
</template>

<script>
export default {
  data: function () {
    return {
      orders: [],
      order: "",
      search: "",
      headers: [
        { text: "Order ID", value: "id", groupable: false },
        { text: "Date", value: "date", groupable: false },
        { text: "Customer", value: "customer.name", groupable: false },
        { value: "actions", groupable: false, sortable: false },
      ],
    };
  },
  methods: {
    getOrders() {
      axios.get("api/orders/status/H").then((res) => {
        this.orders = res.data;
      });
    },
    completePreparing(order) {
      axios
        .post("api/orders/" + order.id + "/status/R")
        .then((res) => {
          this.$store.commit("CLEAR_CURRENT_ORDER");
          console.log(res.data.order.prepared_by);

          this.$socket.emit("remove_order_from_list", res.data.order);
          this.$socket.emit("update_orders_list", res.data.order);
          this.$socket.emit("add_order_to_list", res.data.order);
          this.$socket.emit("update_employee_list", res.data.order.prepared_by);
          this.notifyUser(
            res.data.order.customer.id,
            "Order #" + res.data.order.id + " is ready to be picked up",
            "success"
          );

          this.$toasted.show("Order finished", { type: "success" });
        })
        .catch(() => {
          this.$toasted.show("There was a problem processing your request", {
            type: "error",
          });
        });
    },
    takeOrder(order) {
      axios.get("api/orders/" + order.id).then((res) => {
        this.$store.commit("SET_CURRENT_ORDER", res.data.data[0]);

        axios
          .post("api/orders/" + order.id + "/status/P")
          .then((res) => {
            this.removeOrderFromList(res.data.order);

            this.$socket.emit("update_orders_list", res.data.order);
            this.$socket.emit(
              "update_employee_list",
              res.data.order.prepared_by
            );
            this.notifyUser(
              res.data.order.customer.id,
              "Order #" + res.data.order.id + " is being prepared",
              "success"
            );
            this.$toasted.show("Order began preparation", { type: "success" });
          })
          .catch((e) => {
            this.$toasted.show("There was a problem with the order", {
              type: "error",
            });
          });
      });
    },
    addOrderToList(order) {
      this.orders.push(order);
    },
    removeOrderFromList(order) {
      const index = this.orders.findIndex((v) => v.id === order.id);

      if (index > -1) {
        this.orders.splice(index, 1);
      }
    },
    notifyUser(userId, msg, msgType) {
      axios
        .get("api/users/" + userId)
        .then((res) => {
          const user = res.data;

          let payload = {
            originalUser: this.$store.state.user,
            destinationUser: user,
            message: msg,
            messageType: msgType,
          };

          this.$socket.emit("customer_message", payload);
        })
        .catch(() => {
          console.log("Problem while getting user");
        });
    },
  },
  mounted() {
    this.getOrders();
  },
  sockets: {
    add_order_to_list(orderToAdd) {
      this.addOrderToList(orderToAdd);
    },
    update_orders_list(orderToRemove) {
      this.removeOrderFromList(orderToRemove);
    },
    staff_dashboard_update() {
      this.$store.commit("CLEAR_CURRENT_ORDER");
    },
  },
};
</script>

<style>
</style>