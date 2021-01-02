<template>
  <v-main >
      <v-container class="mw-100">
          <div class="white rounded-lg px-5 py-5 my-5">
                <div class="text-h4 text-center mb-5">Management Dashboard</div>
                <v-row>
                    <v-col>
                        <div class="headline">Employees</div>
                        <v-data-table
                            :headers="employeeHeaders"
                            :items="employees"
                        >
                            
                            <template v-slot:item.photo_url="{ item }">
                                <v-avatar size="40">
                                    <v-img
                                        width="100"
                                        height="100"
                                        :src="`/storage/fotos/${item.photo_url}`"
                                    >
                                        <template v-slot:placeholder>
                                        <v-row class="fill-height ma-0" align="center" justify="center">
                                            <v-progress-circular
                                            indeterminate
                                            color="grey lighten-5"
                                            ></v-progress-circular>
                                        </v-row>
                                        </template>
                                    </v-img>
                                </v-avatar>
                            </template>
                            <template v-slot:item.available_at="{ item }">
                                <v-chip :color="statusColor(item)">
                                    {{ getStatus(item) }}
                                </v-chip>
                            </template>
                        </v-data-table>
                    </v-col>
                    <v-col>
                        <div class="headline">Orders</div>
                        <v-data-table
                            :headers="orderHeaders"
                            :items="activeOrders"
                        >
                            <template v-slot:top>
                                <v-dialog v-model="viewOrderDialog" max-width="500" persistent>
                                    <v-card>
                                        <v-card-title>Order #{{ orderToView.id }}</v-card-title>
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
                                                <tr v-for="item in orderToView.items" :key="item.id">
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
                                                <tr v-if="orderToView.notes != null">
                                                    <td></td>
                                                    <td></td>
                                                    <td><strong>Notes:</strong></td>
                                                    <td>{{ orderToView.notes }}</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td>
                                                        <strong>Total</strong>
                                                    </td>
                                                    <td>
                                                        {{ orderToView.total_price }}€
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </v-simple-table>
                                        <v-card-actions>
                                            <v-spacer></v-spacer>
                                            <v-btn text color="red" @click.prevent="closeOrderDialog">
                                                Close
                                            </v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-dialog>
                                <v-dialog v-model="cancelOrderDialog" max-width="350">
                                    <v-card>
                                        <v-card-title>Are you sure you want to cancel order #{{orderToCancel.id}}?</v-card-title>
                                        <v-card-text>The staff and the customer will be notified</v-card-text>
                                        <v-card-actions>
                                            <v-spacer></v-spacer>
                                            <v-btn
                                                text
                                                color="red"
                                                @click.prevent="cancelOrderDialog = false"
                                            >
                                                Cancel
                                            </v-btn>
                                            <v-btn
                                                text
                                                color="green"
                                                @click.prevent="confirmOrderCancel"
                                            >
                                                Confirm
                                            </v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-dialog>
                            </template>
                            <template v-slot:item.status="{ item }">
                                <v-chip :color="orderStatusColor(item.status)" class="px-5">
                                    {{ item.status}}
                                </v-chip>
                            </template>
                            <template v-slot:item.actions="{ item }">
                                <v-btn 
                                    icon
                                    @click.prevent="viewOrder(item)"
                                >
                                    <v-icon>mdi-eye</v-icon>
                                </v-btn>
                                <v-btn 
                                    icon
                                    @click.prevent="cancelOrder(item)"
                                >
                                    <v-icon>mdi-cancel</v-icon>
                                </v-btn>
                            </template>
                        </v-data-table>
                    </v-col>
                </v-row>
          </div>

      </v-container>
  </v-main>
</template>

<script>
export default {
    data(){
        return {
            employeeHeaders: [
                {text: '', value: 'photo_url'},
                {text: 'Name', value: 'name'},
                {text: 'ID', value: 'id'},
                {text: 'Status', value: 'available_at'},
                {text: 'Started at', value: 'logged_at'},
            ],
            orderHeaders: [
                {text: 'ID', value: 'id'},
                {text: 'Status', value: 'status'},
                {text: 'Prepared by', value: 'prepared_by'},
                {text: 'Delivered by', value: 'delivered_by'},
                {text: 'Updated at', value: 'current_status_at'},
                {text: 'Actions', value: 'actions'},
            ],
            employees: [],
            activeOrders: [],
            viewOrderDialog: false,
            cancelOrderDialog: false,
            orderToView: '',
            orderToCancel: '',
        }
    },
    methods: {
        getEmployees(){
            axios.get('api/users/employees').then(res => {
                this.employees = res.data.data

                this.employees.map(employee => {
                    if(employee.logged_at !== null){
                        employee.logged_at = new Date(employee.logged_at).toLocaleTimeString()
                    }
                })
            }).catch(() => {
                this.$toasted.show('There was a problem while fetching the active employees', {type: 'error'})
            })
        },
        getActiveOrders(){
            axios.get('api/orders/active').then(res => {
                this.activeOrders = res.data.data

                this.activeOrders.map(order => {
                    if(order.prepared_by === null){
                        order.prepared_by = '-'
                    }
                    
                    if(order.delivered_by === null){
                        order.delivered_by = '-'
                    }

                    switch(order.status){
                        case 'H':
                            order.status = 'Holding'
                            break
                        case 'P':
                            order.status = 'Preparing'
                            break
                        case 'R':
                            order.status = 'Ready'
                            break
                        case 'T':
                            order.status = 'In Transit'
                            break
                        case 'D':
                            order.status = 'Delivered'
                            break
                        case 'C':
                            order.status = 'Cancelled'
                            break
                    }
                })
            }).catch(() => {
                this.$toasted.show('There was a problem while fetching the active orders', {type: 'error'})
            })
        },
        statusColor(item){
            if(item.logged_at === null){
                return 'red text-light'
            }else if(item.available_at !== null && item.type === 'EC'){
                return 'yellow lighten-1'
            }else if(item.available_at !== null && item.type === 'ED'){
                return 'deep-purple darken-1 text-light'
            }else{
                return 'green text-light'
            }
        },
        getStatus(item){
            if(item.logged_at === null){
                return 'Offline'
            }else if(item.available_at !== null && item.type === 'EC'){
                return 'Preparing'
            }else if(item.available_at !== null && item.type === 'ED'){
                return 'Delivering'
            }else{
                return 'Available'
            }
        },
        viewOrder(order){
            this.getOrder(order)
            this.viewOrderDialog = true
        },
        closeOrderDialog(){
            this.orderToView = ''
            this.viewOrderDialog = false
        },
        getOrder(order){
            axios.get('api/orders/'+order.id).then(res => {
                this.orderToView = res.data.data[0]
            }).catch(() => {
                this.$toasted.show('There was a problem fetching the order', {type: 'error'})
            })
        },
        cancelOrder(order){
            this.cancelOrderDialog = true
            this.orderToCancel = order
        },
        confirmOrderCancel(){
            axios.post('api/orders/'+this.orderToCancel.id+'/status/C').then(res => {
                this.removeOrderFromList(this.orderToCancel)
                this.cancelOrderDialog = false
                this.orderToCancel = ''
                this.$toasted.show('Order canceled. Customer notified.', {type: 'success'})
            }).catch(() => {
                this.$toasted.show('Something happened while canceling the order', {type: 'error'})
            })
        },
        orderStatusColor(status){
            switch(status){
                case 'H':
                    return 'grey text-light'
                case 'P':
                    return 'yellow lighten-1'
                case 'R':
                    return 'green text-light'
                case 'T':
                    return 'deep-purple darken-1 text-light'
                case 'D':
                    return 'green darken-3 text-light'
                case 'C':
                    return 'red text-light'
            }
        },
        removeOrderFromList(order){
            const index = this.activeOrders.indexOf(order)

            if(index > -1){
                this.activeOrders.splice(index, 1)
            }
        }
    },
    mounted(){
        this.getEmployees()
        this.getActiveOrders()
    }
}
</script>

<style>

</style>