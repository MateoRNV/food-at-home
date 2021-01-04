<template>
  <v-main class="grey lighten-4">
    <v-container fluid>
      <v-main>
        <v-row class="justify-center align-center flex-column">
          <v-col md="6" class="white rounded-lg p-5">
            <div class="text-h4 text-center mb-5">
              Create an account
            </div>
            <v-form lazy-validation>
              <!-- Image -->
              <!-- <v-img :src="photo_preview" max-width="250"></v-img> -->
              <div class="d-flex flex-wrap flex-row justify-space-between">
                <div class="w-50 px-2">
                  <v-text-field
                    label="Full Name"
                    v-model="form.name"
                    :error-messages="errors.name"
                    name="name"
                  ></v-text-field>
                  <v-text-field
                    label="Email"
                    v-model="form.email"
                    :error-messages="errors.email"
                    name="email"
                  >
                  </v-text-field>
                  <v-text-field
                    :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                    :type="show ? 'text' : 'password'"
                    name="password"
                    label="Password"
                    @click:append="show = !show"
                    v-model="form.password"
                    :error-messages="errors.password"
                  ></v-text-field>
                  <v-text-field
                    :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
                    :type="show2 ? 'text' : 'password'"
                    name="password_confirmation"
                    label="Confirm password"
                    @click:append="show2 = !show2"
                    v-model="form.password_confirmation"
                    :error-messages="errors.password"
                  ></v-text-field>
                </div>
                <div class="w-50 px-2">
                  <v-text-field
                    label="Address"
                    v-model="form.address"
                    :error-messages="errors.address"
                    name="address"
                  ></v-text-field>
                  <v-text-field
                    label="Phone"
                    v-model="form.phone"
                    name="phone"
                    :error-messages="errors.phone"
                  ></v-text-field>
                  <v-text-field
                    label="NIF"
                    v-model="form.nif"
                    name="nif"
                    :error-messages="errors.nif"
                  ></v-text-field>
                  <v-file-input
                    show-size
                    prepend-icon="mdi-camera"
                    label="Profile photo"
                    :error-messages="errors.photo_url"
                    truncate-length="5"
                    accept=".jpg,.png,.jpeg"
                    @change="onFileChange"
                  ></v-file-input>
                </div>
              </div>
              <v-row align="center" justify="center" class="mt-5">
                <v-btn color="error" class="mr-4">Reset form</v-btn>
                <v-btn
                  color="grey darken-4 white--text"
                  class="mr-4"
                  @click.prevent="register"
                  >Create account</v-btn
                >
              </v-row>
            </v-form>
          </v-col>
        </v-row>
        <div class="text-subtitle-2 font-weight-regular mt-5 text-center">
          Already have an account?
          <router-link to="/login">Login</router-link>
        </div>
      </v-main>
    </v-container>
  </v-main>
</template>

<script>
export default {
  data() {
    return {
      show: false,
      show2: false,
      form: {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        address: '',
        phone: '',
        nif: '',
        photo_url: ''
      },
      photo_file: '',
      errors: '',
      // photo_preview: '',
    };
  },
  methods: {
    register() {
      // Upload the photo when the field is not empty or not
      if(this.photo_file !== ''){
        this.submitPhoto()
      }else{
        this.createCustomer()
      }
    },
    createCustomer(){
      axios.post("/api/customers", this.form)
        .then(() => {
          console.log("Registered!");
          this.$router.push('/login')
        })
        .catch((e) => {
          console.log("An error ocurred");
          this.errors = e.response.data.errors
        })
    },
    onFileChange(){
      this.photo_file = event.target.files[0]
    },
    submitPhoto(){
      let formData = new FormData()
      formData.append('photo_file', this.photo_file)

      axios.post('/api/users/photos', formData, { headers: { 'Content-Type': 'multipart/form-data'}})
      .then(res => {
        console.log('Photo uploaded with sucess')
        this.form.photo_url = res.data.filename
        this.createCustomer()
      })
      .catch(() => {
        console.log('There was a problem while uploading the photo')
      })
    }
    // preview_photo(){
    //   console.log(this.photo_preview)
    //   console.log(this.form.photo_url)
    //   if(!this.form.photo_url){
    //     this.photo_preview = ''
    //     return
    //   }

    //   this.photo_preview = URL.createObjectURL(this.form.photo_url)
    // }
  },
};
</script>