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
              <v-text-field label="Email" v-model="credentials.email"></v-text-field>
              <v-text-field label="Password" v-model="credentials.password"></v-text-field>
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
      rules: {
        required: (value) => !!value || "Required",
      },
      errors: []
    };
  },
  methods: {
    login(){
      this.$store.commit('clearUser')
      axios.get('/sanctum/csrf-cookie').then(res => {
        axios.post('/api/login', this.credentials).then( res => {
          this.$store.commit('setUser', res.data)
          this.$toasted.show('User is authenticated', {type: 'success'})
        }).catch(e => {
          this.$toasted.show('Invalid auth', {type: 'error'})
        })
      })
    }
  },
};
</script>
