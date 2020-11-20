<template>
  <v-main>
    <v-row class="justify-center">
      <v-col md="auto" class="white rounded-lg p-5">
        <div class="text-h4 text-center mb-4 font-weight-bold">
          Ready to order?
        </div>
        <v-form>
          <v-text-field label="Email" v-model="form.email"></v-text-field>
          <v-text-field
            :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
            :rules="[rules.required]"
            :type="show ? 'text' : 'password'"
            name="input-10-1"
            label="Password"
            @click:append="show = !show"
            v-model="form.password"
          ></v-text-field>
          <v-btn color="grey darken-4 white--text mt-4" block @click.prevent="attemptLogin">Login</v-btn>
          <div class="text-subtitle-2 font-weight-regular mt-5 text-center">
            Don't have an account yet?
            <router-link to="/register">Register</router-link>
          </div>
        </v-form>
      </v-col>
    </v-row>
  </v-main>
</template>

<script>
export default {
  data() {
    return {
      show: false,
      form: {
        email: '',
        password: ''
      },
      rules: {
        required: (value) => !!value || "Required",
      },
      errors: []
    };
  },
  methods: {  
    attemptLogin(){
      axios.post('/api/login', this.form).then(() => {
        console.log("Login!");
      }).catch((e) => {
        this.errors = e.response.data.errros
      })
    }
  },
};
</script>
