<template>
  <v-main class="grey lighten-4">
    <v-container fluid>
      <v-data-table :headers="headers" :items="$store.state.products" :items-per-page="10">
        <template v-slot:top>
          <v-toolbar flat>
            <v-toolbar-title>Products</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-dialog v-model="dialog" max-width="500" persistent>
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  color="primary"
                  dark
                  class="mb-2"
                  v-bind="attrs"
                  v-on="on"
                >
                  Add Product
                </v-btn>
              </template>
              <v-card>
                <v-card-title class="justify-center">
                  <span class="headline">Add product</span>
                </v-card-title>

                <v-card-text>
                  <v-container>
                    <v-text-field
                      label="Name"
                      outlined
                      v-model="form.name"
                      name="name"
                      :error-messages="errors.name"
                    >
                    </v-text-field>
                    <v-textarea
                      label="Description"
                      outlined
                      v-model="form.description"
                      name="description"
                      :error-messages="errors.description"
                      counter
                      no-resize
                    >
                    </v-textarea>
                    <v-row>
                      <v-col>
                        <v-select
                          :items="['drink', 'hot dish', 'cold dish', 'dessert']"
                          label="Type"
                          :error-messages="errors.type"
                          outlined
                          v-model="form.type"
                        >
                        </v-select>
                      </v-col>
                      <v-col>
                        <v-text-field
                          label="Price"
                          outlined
                          v-model="form.price"
                          name="price"
                          suffix="€"
                          type="number"
                          :error-messages="errors.price"
                        >
                        </v-text-field>
                      </v-col>
                    </v-row>
                    <v-file-input
                        show-size
                        outlined
                        prepend-inner-icon="mdi-camera"
                        prepend-icon=""
                        label="Product photo"
                        :error-messages="errors.photo_url"
                        accept=".jpg,.png,.jpeg"
                        @change="onFileChange"
                    ></v-file-input>                            
                  </v-container>
                </v-card-text>

                <v-card-actions class="pb-2 pr-2">
                  <v-spacer></v-spacer>
                  <v-btn @click.prevent="dialog = false" text color="red">
                    Cancel
                  </v-btn>
                  <v-btn @click.prevent="createProduct" text color="green">
                    Create
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
            <v-dialog v-model="dialogDelete" max-width="300">
                <v-card>
                    <v-card-title>Are you sure?</v-card-title>
                    <v-card-text>Please confirm you want to delete {{ productToDelete.name }}.</v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="red"
                            @click.prevent="dialogDelete = false"
                            text
                        >
                            Cancel
                        </v-btn>
                        <v-btn
                            color="green"
                            @click.prevent="deleteProduct(productToDelete)"
                            text
                        >
                            Confirm
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <v-dialog v-model="dialogEdit" max-width="500" persistent>
                <v-card>
                    <v-card-title class="justify-center">
                    <span class="headline">Edit product</span>
                    </v-card-title>

                    <v-card-text>
                    <v-container>
                        <v-text-field
                        label="Name"
                        outlined
                        v-model="form.name"
                        name="name"
                        :error-messages="errors.name"
                        >
                        </v-text-field>
                        <v-textarea
                        label="Description"
                        outlined
                        v-model="form.description"
                        name="description"
                        :error-messages="errors.description"
                        counter
                        no-resize
                        >
                        </v-textarea>
                        <v-row>
                        <v-col>
                            <v-select
                            :items="['drink', 'hot dish', 'cold dish', 'dessert']"
                            label="Type"
                            :error-messages="errors.type"
                            outlined
                            v-model="form.type"
                            >
                            </v-select>
                        </v-col>
                        <v-col>
                            <v-text-field
                            label="Price"
                            outlined
                            v-model="form.price"
                            name="price"
                            suffix="€"
                            type="number"
                            :error-messages="errors.price"
                            >
                            </v-text-field>
                        </v-col>
                        </v-row>
                        <v-file-input
                            show-size
                            outlined
                            prepend-inner-icon="mdi-camera"
                            prepend-icon=""
                            label="Product photo"
                            :error-messages="errors.photo_url"
                            accept=".jpg,.png,.jpeg"
                            @change="onFileChange"
                        ></v-file-input>                            
                    </v-container>
                    </v-card-text>

                    <v-card-actions class="pb-2 pr-2">
                    <v-spacer></v-spacer>
                    <v-btn @click.prevent="cancelEdit" text color="red">
                        Cancel
                    </v-btn>
                    <v-btn @click.prevent="updateEdit" text color="green">
                        Update
                    </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
          </v-toolbar>
        </template>
        <template v-slot:item.photo_url="{ item }">
          <v-img
            width="100"
            height="100"
            :src="`/storage/products/${item.photo_url}`"
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
        </template>
        <template v-slot:item.price="{ item }">
            {{ item.price }}€
        </template>

        <template v-slot:item.actions="{ item }">
            <v-icon
                small
                @click.prevent="editItemDialog(item)"
                class="mr-1"
            >
                mdi-pencil
            </v-icon>
            <v-icon
                small
                @click.prevent="deleteItemDialog(item)"
            >
                mdi-delete
            </v-icon>
        </template> 
      </v-data-table>
    </v-container>
  </v-main>
</template>

<script>
export default {
  data() {
    return {
      productToDelete: '',
      productToEdit: '',
      form: {
        name: "",
        type: "",
        description: "",
        price: "",
        photo_url: ''
      },
      photo_file: '',
      errors: "",
      dialog: false,
      dialogDelete: false,
      dialogEdit: false,
      headers: [
        { text: "Photo", value: "photo_url", sortable: false },
        { text: "Name", value: "name" },
        { text: "Type", value: "type" },
        { text: "Description", value: "description" },
        { text: "Price", value: "price" },
        { text: "Actions", value: 'actions', sortable: false },
      ],
    };
  },
  methods: {
    // getProducts() {
    //   axios
    //     .get("api/products")
    //     .then((res) => {
    //       this.products = res.data.data;
    //     })
    //     .catch(() => {
    //       console.error("There was a problem while fetching products");
    //     });
    // },
    insertProduct() {
      axios.post("api/products", this.form)
        .then(res => {
          this.$store.commit('ADD_PRODUCT_TO_LIST', res.data[1])
          this.$toasted.show('Product created successfully', {type: 'success'})
          this.dialog = false
        })
        .catch((e) => {
          this.errors = e.response.data.errors
        });
    },
    deleteItemDialog(item){
        this.dialogDelete = true
        this.productToDelete = item
    },
    editItemDialog(item){
        this.dialogEdit = true

        this.productToEdit = item
        
        this.form.name = item.name
        this.form.description = item.description
        this.form.type = item.type
        this.form.price = item.price

    },
    clearForm(){
        this.form.name = ''
        this.form.type = ''
        this.form.description = ''
        this.form.price = ''
        this.form.photo_url = ''
        this.form.photo_file = ''
        
    },
    cancelEdit(){
        this.dialogEdit = false

        this.form.name = ''
        this.form.description = ''
        this.form.type = ''
        this.form.price = ''
    },
    createProduct(){
      let formData = new FormData()
      formData.append('photo_file', this.photo_file)

      axios.post('/api/products/photos', formData, { headers: { 'Content-Type': 'multipart/form-data'}})
      .then(res => {
        console.log('Photo uploaded with sucess')
        this.form.photo_url = res.data.filename
        this.insertProduct()
      })
      .catch(() => {
        console.log('There was a problem while uploading the photo')
      })
    },
    updateEdit(){
        if(this.photo_file !== ''){
            this.updatePhoto()
        }else{
            this.form.photo_url = this.productToEdit.photo_url
            this.updateProduct(this.productToEdit)
        }
    },
    updatePhoto(){
        let formData = new FormData()
        
        formData.append('photo_file', this.photo_file)

        axios.post('/api/products/photos/' + this.productToEdit.id, formData, { headers: { 'Content-Type': 'multipart/form-data'}})
        .then(res => {
            console.log('Photo updated sucessfully')
            this.form.photo_url = res.data.filename
            console.log(this.form.photo_url)
            console.log(res.data.filename)
            console.log(res)
            this.updateProduct(this.productToEdit)
        })
        .catch(e => {
            console.log('There was a problem while updating the photo')
        })
    },
    updateProduct(product){
        axios.put('/api/products/' + product.id, this.form)
        .then(() => {
            // this.getProducts()
            this.dialogEdit = false
            this.productToEdit = ''
            this.clearForm()
            this.$toasted.show('Product updated successfully', {type: 'success'})
        })
        .catch((e) => {
            this.errors = e.response.data.errors
            this.$toasted.show('There was a problem updating the product', {type: 'error'})
        })
    },
    deleteProduct(product){
      
      axios.delete('/api/products/' + product.id)
        .then(res => {
            this.$store.commit('REMOVE_PRODUCT_FROM_LIST', product)

            this.dialogDelete = false
            this.productToDelete = ''
            this.$toasted.show('Product deleted successfully', {type: 'success'})
        })
        .catch(() => {
            this.$toasted.show('There was a problem deleting the product', {type: 'error'})
        })
    },
    onFileChange(){
      this.photo_file = event.target.files[0]
      console.log("Yeah i changed")
    },
  },
  mounted() {
    //this.getProducts();
  },
};
</script>

<style>
</style>