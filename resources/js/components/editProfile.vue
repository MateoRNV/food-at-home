<template>
  <v-main class="grey lighten-4">
    <v-container fluid>
      <v-main>
        <v-row class="justify-center align-center flex-column">
          <v-col md="6" class="white rounded-lg p-5">
            <div class="text-h4 text-center mb-5">Update your account</div>
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
                    :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                    :type="show ? 'text' : 'password'"
                    name="password"
                    label="Password"
                    @click:append="show = !show"
                    v-model="form.password"
                    :error-messages="errors.password"
                  ></v-text-field>
                  <v-text-field
                    v-if="isClient"
                    label="Phone"
                    v-model="form.phone"
                    name="phone"
                    :error-messages="errors.phone"
                  ></v-text-field>
                  <v-text-field
                    v-if="isClient"
                    label="NIF"
                    v-model="form.nif"
                    name="nif"
                    :error-messages="errors.nif"
                  ></v-text-field>
                </div>
                <div class="w-50 px-2">
                  <v-text-field
                    label="Email"
                    v-model="form.email"
                    :error-messages="errors.email"
                    name="email"
                  >
                  </v-text-field>
                  <v-text-field
                    :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
                    :type="show2 ? 'text' : 'password'"
                    name="password_confirmation"
                    label="Confirm password"
                    @click:append="show2 = !show2"
                    v-model="form.password_confirmation"
                    :error-messages="errors.password"
                  ></v-text-field>
                  <v-text-field
                    v-if="isClient"
                    label="Address"
                    v-model="form.address"
                    :error-messages="errors.address"
                    name="address"
                  ></v-text-field>
                </div>
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
              <v-row align="center" justify="center" class="mt-5">
                <v-btn color="error" to="/products" class="mr-4"> Cancel</v-btn>
                <v-btn
                  color="grey darken-4 white--text"
                  class="mr-4"
                  @click.prevent="update"
                  >Update your account</v-btn
                >
              </v-row>
            </v-form>
          </v-col>
        </v-row>
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
      isClient: false,
      form: "",
      user: "",
      photo_file: "",
      errors: "",
      // photo_preview: '',
    };
  },
  methods: {
    getUser() {
      axios.get("api/users/" + this.$store.state.user.id).then((res) => {
        this.user = res.data;
        axios.get("api/customers/" + this.$store.state.user.id).then((res) => {
          this.form = res.data;
          // console.log(this.form);
          this.form.name = this.user.name;
          this.form.email = this.user.email;
        });
      });
    },
    onFileChange() {
      this.photo_file = event.target.files[0];
    },
    update() {
      // Upload the photo when the field is not empty or not
      if (this.photo_file !== "") {
        console.log("update photo");
        this.updatePhoto();
      } else {
        this.form.photo_url = null;
        console.log("not update " + this.form.photo_url);
        this.isCustomer();
      }
    },
    isCustomer() {
      if (this.$store.state.user.type == "C") {
        this.updateCustomer();
      } else {
        this.updateEmployee();
      }
    },
    submitPhoto() {
      let formData = new FormData();
      formData.append("photo_file", this.photo_file);

      axios
        .post("/api/users/photos", formData, {
          headers: { "Content-Type": "multipart/form-data" },
        })
        .then((res) => {
          console.log("Photo uploaded with sucess");
          this.form.photo_url = res.data.filename;
          this.updateCustomer();
        })
        .catch(() => {
          console.log("There was a problem while uploading the photo");
        });
    },
    updatePhoto() {
      let formData = new FormData();

      formData.append("photo_file", this.photo_file);

      axios
        .post("/api/users/" + this.$store.state.user.id+"/photos", formData, {
          headers: { "Content-Type": "multipart/form-data" },
        })
        .then((res) => {
          console.log("Photo updated sucessfully");
          this.form.photo_url = res.data.filename;
          console.log(this.form.photo_url);
          //console.log(res.data.filename);
          // console.log(res);
          this.isCustomer();
        })
        .catch((e) => {
          console.log("There was a problem while updating the photo");
        });
    },

    updateCustomer() {
      if (typeof this.form.password === "undefined") {
        this.form.password = null;
        console.log("here");
      }
      console.log(this.form.password);
      axios
        .put("/api/customers/" + this.$store.state.user.id, this.form)
        .then((res) => {
          console.log("Updated!");
          console.log(res.data);
          this.$toasted.show("Updated succed", {
            type: "success",
            duration: 3000,
          });
          this.$router.push("/products");
        })
        .catch((e) => {
          console.log("An error ocurred");
          this.errors = e.response.data.errors;
          console.log(e.response.data.errors);
          this.$toasted.show("Updated failed", {
            type: "error",
            duration: 3000,
          });
        });
    },
    updateEmployee() {
      if (typeof this.form.password === "undefined") {
        this.form.password = null;
        console.log("here");
      }
      axios
        .put("/api/users/" + this.$store.state.user.id, this.form)
        .then(() => {
          console.log("Updated!");
          console.log(this.user);
          this.$toasted.show("Updated succed", {
            type: "success",
            duration: 3000,
          });
          this.$router.push("/products");
        })
        .catch((e) => {
          console.log("Oh oh!");
          console.log(e.response.data.errors);
          this.errors = e.response.data.errors;
          this.$toasted.show("Updated failed", {
            type: "error",
            duration: 3000,
          });
        });
    },

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
  mounted() {
    this.form = this.$store.state.user;
    this.form.password = null;
    if (this.form.type == "C") {
      this.isClient = true;
      this.getUser();
    }
  },
};
</script>