<template>
  <v-main class="grey lighten-4">
    <v-container fluid>
      <v-main>
        <v-row class="justify-center">
          <v-col md="auto" class="white rounded-lg p-5">
            <div class="text-h4 text-center mb-4 font-weight-bold">
              Ready to order?
            </div>
            <v-form>
              <v-text-field label="Email" v-model="credentials.email" :error-messages="emailErrors" :rules="rules"></v-text-field>
              <v-text-field
                    :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                    :rules="rules"
                    :error-messages="passwordErrors"
                    :type="show ? 'text' : 'password'"
                    name="password"
                    label="Password"
                    @click:append="show = !show"
                    v-model="credentials.password"
                  ></v-text-field>
              <v-btn color="grey darken-4 white--text mt-4" block @click.prevent="login">Login</v-btn>
              <div class="text-subtitle-2 font-weight-regular mt-5 text-center">
                Don't have an account yet?
                <router-link to="/register">Register</router-link>
              </div>
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
      credentials: {
        email: '',
        password: ''
      },
      rules:[
        v => !!v || "This field is required",
      ],
      emailErrors: [],
      passwordErrors: []
    };
  },
  methods: {
    login(){
      this.$store.commit('clearUser')
      this.emailErrors = ""
      this.passwordErrors = ""
      axios.get('/sanctum/csrf-cookie').then(res => {
        axios.post('/api/login', this.credentials).then( res => {
          this.$store.commit('setUser', res.data)
          this.$toasted.show('Welcome back', {type: 'success'})
          this.$router.push('/products')
        }).catch(e => {
          this.$toasted.show('There was a problem, please try again', {type: 'error'})
          
          if(this.credentials.email === '')
            this.emailErrors = "Wrong email"

          if(this.credentials.password === '')
            this.passwordErrors = "Wrong password"

          
        })
      })
    }
  },
};
</script>
