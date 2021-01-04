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
                            <template v-slot:top>
                                <v-dialog v-model="currentOrderDialog" max-width="500" persistent>
                                    <v-card>
                                        <v-card-title>Order #{{ currentOrder.id }}</v-card-title>
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
                                                <tr v-if="currentOrder.notes != null">
                                                    <td></td>
                                                    <td></td>
                                                    <td><strong>Notes:</strong></td>
                                                    <td>{{ currentOrder.notes }}</td>
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

                                        <v-card-actions>
                                            <v-spacer></v-spacer>
                                            <v-btn text color="red" @click.prevent="closeCurrentOrderDialog">Close</v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-dialog>
                            </template>
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

                            <template v-slot:item.type="{ item }">
                                {{ typeToStr(item.type) }}
                            </template>

                            <template v-slot:item.available_at="{ item }">
                                <v-chip :color="statusColor(item)">
                                    {{ getStatus(item) }}
                                </v-chip>
                            </template>

                            <template v-slot:item.logged_at="{ item }">
                                {{ (item.logged_at !== null) ? new Date(item.logged_at).toLocaleTimeString() : '-' }}
                            </template>

                            <template v-slot:item.actions="{ item }">
                                <v-btn
                                    icon
                                    :disabled="(item.logged_at === null || item.available_at !== null || (item.logged_at !== null && item.available_at !== null)) || item.type === 'EM' "
                                    @click.prevent="viewCurrentOrder(item.id)"
                                >
                                    <v-icon>mdi-magnify</v-icon>
                                </v-btn>                            
                            </template>
                            
                            <template v-slot:no-data>
                                No employees to show
                            </template>
                        </v-data-table>
                    </v-col>
                    <v-col>
                        <div class="headline">Orders</div>
                        <v-data-table
                            :headers="orderHeaders"
                            :items="activeOrders"
                            :sort-by.sync="sortOrdersBy"
                            :sort-desc.sync="sortDesc"
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
                                    {{ getOrderStatus(item.status) }}
                                </v-chip>
                            </template>
                            <template v-slot:item.actions="{ item }">
                                <v-btn 
                                    icon
                                    @click.prevent="viewOrder(item)"
                                >
                                    <v-icon>mdi-magnify</v-icon>
                                </v-btn>
                                <v-btn 
                                    icon
                                    @click.prevent="cancelOrder(item)"
                                >
                                    <v-icon>mdi-cancel</v-icon>
                                </v-btn>
                            </template>
                            <template v-slot:item.current_status_at="{ item }">
                                {{ (item.current_status_at !== null) ? new Date(item.current_status_at).toLocaleTimeString() : '-' }}
                            </template>

                            <template v-slot:no-data>
                                There's currently no orders
                            </template>
                            <template v-slot:loading>
                                Loading orders
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
                {text: '', value: 'photo_url', sortable: false},
                {text: 'Name', value: 'name', sortable: false},
                {text: 'ID', value: 'id', sortable: false},
                {text: 'Type', value: 'type'},
                {text: 'Status', value: 'available_at'},
                {text: 'Logged at', value: 'logged_at', sortable: false},
                {text: 'Actions', value: 'actions', sortable: false, },
            ],
            orderHeaders: [
                {text: 'ID', value: 'id', sortable: false},
                {text: 'Status', value: 'status'},
                {text: 'Prepared by', value: 'prepared_by', sortable: false},
                {text: 'Delivered by', value: 'delivered_by', sortable: false},
                {text: 'Updated at', value: 'current_status_at', sortable: false},
                {text: 'Actions', value: 'actions', sortable: false},
            ],
            sortOrdersBy: 'current_status_at',
            sortDesc: true,
            employees: [],
            activeOrders: [],
            viewOrderDialog: false,
            cancelOrderDialog: false,
            currentOrderDialog: false,
            orderToView: '',
            orderToCancel: '',
            currentOrder: ''
        }
    },
    methods: {
        getEmployees(){
            axios.get('api/users/employees').then(res => {
                this.employees = res.data.data
            }).catch(() => {
                this.$toasted.show('There was a problem while fetching the active employees', {type: 'error'})
            })
        },
        getActiveOrders(){
            axios.get('api/orders/active').then(res => {
                this.activeOrders = res.data.data
            }).catch(() => {
                this.$toasted.show('There was a problem while fetching the active orders', {type: 'error'})
            })
        },
        statusColor(item){
            if(item.logged_at === null){
                return 'red text-light'
            }else if(item.available_at === null && item.type === 'EC'){
                return 'yellow lighten-1'
            }else if(item.available_at === null && item.type === 'ED'){
                return 'deep-purple darken-1 text-light'
            }else{
                return 'green text-light'
            }
        },
        getStatus(item){
            if(item.logged_at === null){
                return 'Offline'
            }else if(item.available_at === null && item.type === 'EC'){
                return 'Preparing order'
            }else if(item.available_at === null && item.type === 'ED'){
                return 'Delivering order'
            }else if(item.available_at !== null || item.logged_at !== null){
                return 'Available'
            }
        },
        getOrderStatus(item){
            switch(item){
                case 'H':
                    return 'Holding'
                    break
                case 'P':
                    return 'Preparing'
                    break
                case 'R':
                    return 'Ready'
                    break
                case 'T':
                    return 'In Transit'
                    break
                case 'D':
                    return 'Delivered'
                    break
                case 'C':
                    return 'Cancelled'
                    break
            }
        },
        typeToStr(type){
            switch(type){
                case 'EC':
                    return 'Cook'
                case 'ED':
                    return 'Delivery'
                case 'EM':
                    return 'Manager'
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

                console.log(res)
                console.log(this.orderToCancel)

                // If order is being prepared or in transit
                if(this.orderToCancel.status === 'P'){
                    this.clearCurrentOrder(this.orderToCancel.prepared_by)
                    this.notifyUser(this.orderToCancel.prepared_by, 'The order you\'re preparing has been cancelled', 'error')
                    this.refreshUser(this.orderToCancel.prepared_by)
                    this.$socket.emit('update_employee_list', this.orderToCancel.prepared_by)
                }else if(this.orderToCancel.status === 'T'){
                    this.clearCurrentOrder(this.orderToCancel.delivered_by)
                    this.notifyUser(this.orderToCancel.delivered_by, 'The order you\'re delivering has been cancelled', 'error')
                    this.refreshUser(this.orderToCancel.delivered_by)
                    this.$socket.emit('update_employee_list', this.orderToCancel.delivered_by)
                }
                
                this.notifyUser(res.data.order.customer.id, 'Order #' + this.orderToCancel.id + ' has been cancelled', 'error')
                this.$socket.emit('update_orders_list', res.data.order)
                this.$socket.emit('remove_order_from_list', res.data.order)

                this.orderToCancel = ''
                this.$toasted.show('Order canceled. Customer notified.', {type: 'success'})
            }).catch(e => {
                this.$toasted.show('Something happened while canceling the order', {type: 'error'})
                console.log(e)
            })
        },
        viewCurrentOrder(userId){
            this.currentOrderDialog = true
            this.getCurrentOrder(userId)
        },
        getCurrentOrder(userId){
            axios.get('api/users/employees/'+userId+'/current').then(res => {
                this.currentOrder = res.data.data[0]
            }).catch(() => {
                this.$toasted.show('There was a problem fetching the order', {type: 'error'})
            })
        },
        closeCurrentOrderDialog(){
            this.currentOrder = ''
            this.currentOrderDialog = false
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
            }
        },
        removeOrderFromList(order){
            const index = this.activeOrders.indexOf(order)

            if(index > -1){
                this.activeOrders.splice(index, 1)
            }
        },
        notifyUser(userId, msg, msgType){
            axios.get('api/users/'+userId).then(res => {
                const user = res.data

                let payload = {
                    originalUser: this.$store.state.user,
                    destinationUser: user,
                    message: msg,
                    messageType: msgType
                }
                
                this.$socket.emit('customer_message', payload)
            }).catch(() => {
                console.log('Problem while getting user')
            })
        },
        clearCurrentOrder(userId){
            axios.get('api/users/' + userId).then( res => {
                const user = res.data

                let payload = {
                    destinationUser: user
                }
    
                this.$socket.emit('staff_dashboard_update', payload)
            }).catch(() => {
                console.log('Problem while getting delivery user')
            })
        },
        refreshOrder(order){
            let index = this.activeOrders.findIndex(v => v.id === order.id)
            
            if(index > -1){
                this.activeOrders.splice(index, 1, order)
            }
        },
        removeDeletedOrder(order){
            let index = this.activeOrders.findIndex(v => v.id === order.id)

            if(index > -1){
                this.activeOrders.splice(index, 1)
            }
        },
        addOrder(order){
            this.activeOrders.push(order)
        },
        refreshUser(userId){
            axios.get('api/users/' + userId).then(res => {
                
                let index = this.employees.findIndex(v => v.id === userId)
    
                if(index > -1){
                    this.employees.splice(index, 1, res.data)
                }
            }).catch(() => {
                console.log('something happened when refreshing :C')
            })
        }
    },
    mounted(){
        this.getEmployees()
        this.getActiveOrders()
    },
    sockets: {
        update_orders_list(orderToUpdate){
            this.refreshOrder(orderToUpdate)
        },
        remove_order_from_list(orderToRemove){
            this.removeDeletedOrder(orderToRemove)
        },
        add_order_to_list(orderToAdd){
            this.addOrder(orderToAdd)
        },
        update_employee_list(employeeToUpdate){
            this.refreshUser(employeeToUpdate)
        }
    }
    
}
</script>

<style>

</style>