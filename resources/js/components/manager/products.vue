<template>
  <v-main class="grey lighten-4">
    <v-container fluid>
      <v-data-table :headers="headers" :items="products" :items-per-page="10">
        <template v-slot:top>
          <v-toolbar flat>
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

                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn @click.prevent="dialog = false" text color="red">
                    Cancel
                  </v-btn>
                  <v-btn @click.prevent="submitPhoto" text color="green">
                    Create
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
      </v-data-table>
    </v-container>
  </v-main>
</template>

<script>
export default {
  data() {
    return {
      products: [],
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
      headers: [
        { text: "Photo", value: "photo_url" },
        { text: "Name", value: "name" },
        { text: "Type", value: "type" },
        { text: "Description", value: "description" },
        { text: "Price", value: "price" },
      ],
    };
  },
  methods: {
    getProducts() {
      axios
        .get("api/products")
        .then((res) => {
          this.products = res.data.data;
        })
        .catch(() => {
          console.error("There was a problem while fetching products");
        });
    },
    createProduct() {
      axios.post("api/products", this.form)
        .then(() => {
          this.getProducts()
          this.$toasted.show('Product created successfully', {type: 'success'})
          this.dialog = false
        })
        .catch((e) => {
          this.errors = e.response.data.errors;
        });
    },
    onFileChange(){
      this.photo_file = event.target.files[0]
    },
    submitPhoto(){
      let formData = new FormData()
      formData.append('photo_file', this.photo_file)

      axios.post('/api/products/photos', formData, { headers: { 'Content-Type': 'multipart/form-data'}})
      .then(res => {
        console.log('Photo uploaded with sucess')
        this.form.photo_url = res.data.filename
        this.createProduct()
      })
      .catch(() => {
        console.log('There was a problem while uploading the photo')
      })
    }
  },
  mounted() {
    this.getProducts();
  },
};
</script>

<style>
</style>