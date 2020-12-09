<template>
  <v-main class="grey lighten-4">
    <v-container fluid>
      <v-main>
        <v-row class="justify-center align-center flex-column">
          <div v-for="(error, index) in errors" :key="index">
            <v-alert
              border="right"
              colored-border
              type="error"
              elevation="2"
              v-if="errors"
            >
              <v-list-item v-for="err in error">
                <v-list-item-content >
                  <v-list-item-title>{{ err }}</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-alert>
          </div>
          <v-col md="6" class="white rounded-lg p-5">
            <div class="text-h5 text-center mb-4 font-weight-bold">
              Create an account
            </div>
            <v-form lazy-validation>
              <div class="d-flex flex-wrap flex-row justify-space-between">
                <div>
                  <v-text-field
                    label="Full Name"
                    v-model="form.name"
                    :rules="nameRules"
                    name="name"
                  ></v-text-field>
                  <v-text-field
                    label="Email"
                    v-model="form.email"
                    :rules="emailRules"
                    name="email"
                  >
                  </v-text-field>
                  <v-text-field
                    :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                    :rules="passwordRules"
                    :type="show ? 'text' : 'password'"
                    name="password"
                    label="Password"
                    @click:append="show = !show"
                    v-model="form.password"
                  ></v-text-field>
                  <v-text-field
                    :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
                    :rules="passwordRules"
                    :type="show2 ? 'text' : 'password'"
                    name="password_confirmation"
                    label="Confirm password"
                    @click:append="show2 = !show2"
                    v-model="form.password_confirmation"
                  ></v-text-field>
                </div>
                <div>
                  <v-text-field
                    label="Address"
                    v-model="form.address"
                    :rules="addressRules"
                    name="address"
                  ></v-text-field>
                  <v-text-field
                    label="Phone"
                    v-model="form.phone"
                    :rules="phoneRules"
                    name="phone"
                  ></v-text-field>
                  <v-text-field
                    label="NIF"
                    v-model="form.nif"
                    name="nif"
                  ></v-text-field>
                  <v-file-input
                    show-size
                    prepend-icon="mdi-camera"
                    label="Profile photo"
                  ></v-file-input>
                </div>
              </div>
              <v-row align="center" justify="center" class="mt-5">
                <v-btn color="error" class="mr-4">Reset form</v-btn>
                <v-btn
                  color="grey darken-4 white--text"
                  class="mr-4"
                  @click.prevent="registerAccount"
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
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
        address: "",
        phone: "",
        nif: "",
      },
      errors: '',
      nameRules:[
        v => !!v || 'Your full name is required'
      ],
      emailRules:[
        v => !!v || 'Your email is required'
      ],
      passwordRules:[
        v => !!v || 'A password is required',
      ],
      addressRules:[
        v => !!v || 'Your address is required'
      ],
      phoneRules:[
        v => !!v || 'Your phone number is required'
      ],
    };
  },
  methods: {
    registerAccount() {
      axios.post("/api/customers", this.form)
        .then(() => {
          console.log("Registered!");
          this.$router.push('/login')
        })
        .catch((e) => {
          console.log("An error ocurred");
          this.errors = e.response.data.errors
          console.log(e.response.data.errors)
        });
    },
  },
};
</script>