<template>
  <v-container fluid>
    <v-row class="justify-center">
      <v-col class="white">
        <div class="text-h5 text-center mb-4 font-weight-bold">Create User</div>
        <v-form>
          <div class="d-flex flex-wrap flex-row justify-space-between">
            <div>
              <v-text-field
                label="Fullname"
                :rules="nameRules"
                v-model="form.name"
              ></v-text-field>
              <v-text-field
                label="Email"
                :rules="emailRules"
                required
                v-model="form.email"
              ></v-text-field>
              <v-text-field
                :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                :rules="[rules.required]"
                :type="show ? 'text' : 'password'"
                name="input-10-1"
                label="Password"
                @click:append="show = !show"
                v-model="form.password"
              ></v-text-field>
            </div>
            <div>
              <v-text-field label="Type" v-model="form.phone"></v-text-field>
              <v-text-field
                label="Email confirmation"
                v-model="form.phone"
              ></v-text-field>
              <v-text-field
                label="Pasword confirmation"
                v-model="form.nif"
              ></v-text-field>
            </div>
          </div>
          <v-file-input
            show-size
            prepend-icon="mdi-camera"
            label="Profile photo"
          ></v-file-input>
          <v-row align="center" justify="center">
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
  </v-container>
</template>


<script>
export default {
  data() {
    return {
      show: false,
      form: {
        name: "",
        email: "",
        password: "",
        address: "",
        phone: "",
        nif: "",
      },
      rules: {
        required: (value) => !!value || "Required",
      },
      errors: [],
      nameRules: [
        (v) => !!v || "Name is required",
      ],
      emailRules: [
        (v) => !!v || "E-mail is required",
        (v) => /.+@.+/.test(v) || "E-mail invalid",
      ],
    };
  },
  methods: {
    registerAccount() {
      axios
        .post("/api/register", this.form)
        .then(() => {
          console.log("Registered!");
        })
        .catch((e) => {
          console.log("Oh oh!");
          console.log(e.response.data.errors);
          this.errors = e.response.data.errors;
        });
    },
  },
};
</script>

<style>
</style>