<template>
  <v-container fluid>
    <v-row class="justify-center">
      <v-col class="white">
        <div class="text-h5 text-center mb-4 font-weight-bold">
          {{ formTitle }}
        </div>
        <v-form>
          <div class="d-flex flex-wrap flex-row justify-space-between">
            <div>
              <v-text-field
                label="Fullname"
                :rules="nameRules"
                v-model="user.name"
              ></v-text-field>

              <v-text-field
                :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                :rules="[rules.required]"
                :type="show ? 'text' : 'password'"
                name="input-10-1"
                label="Password"
                @click:append="show = !show"
                v-model="user.password"
              ></v-text-field>
            </div>
            <div>
              <v-text-field
                label="Email"
                :rules="emailRules"
                required
                v-model="user.email"
              ></v-text-field>
            </div>
          </div>
          <v-select
            :items="userTypes"
            label="Type of user"
            v-model="user.type"
            solo
          ></v-select>
          <v-file-input
            show-size
            prepend-icon="mdi-camera"
            label="Profile photo"
          ></v-file-input>
          <v-row align="center" justify="center">
            <v-btn color="error" class="mr-4">Reset</v-btn>
            <v-btn
              v-if="isNew"
              color="grey darken-4 white--text"
              class="mr-4"
              @click.prevent="registerAccount"
              >Create account</v-btn
            >
            <v-btn
              v-else
              color="grey darken-4 white--text"
              class="mr-4"
              @click.prevent="updateAccount"
              >Update</v-btn
            >
          </v-row>
        </v-form>
      </v-col>
    </v-row>
  </v-container>
</template>


<script>
export default {
  props: ["formTitle", "user", "isNew"],
  data() {
    return {
      userTypes: ["EC", "ED", "EM"],
      show: false,
      success: false,

      rules: {
        required: (value) => !!value || "Required",
      },
      errors: [],
      nameRules: [(v) => !!v || "Name is required"],
      emailRules: [
        (v) => !!v || "E-mail is required",
        (v) => /.+@.+/.test(v) || "E-mail invalid",
      ],
    };
  },
  methods: {
    registerAccount() {
      console.log(this.user);
      axios
        .post("/api/users", this.user)
        .then(() => {
          console.log("Registered!");
          this.$toast.success("User created successfully!").goAway(3000);
          this.success = true;
        })
        .catch((e) => {
          console.log("Oh oh!");
          console.log(e.response.data.errors);
          this.errors = e.response.data.errors;
          this.$toasted
            .show("There was a problem, please try again", { type: "error" })
            .goAway(3000);
        });
      this.$emit("registered", this.success);
    },
    updateAccount() {
        console.log(this.user.id)
        axios
          .put("/api/users/" + this.user.id, this.user)
          .then(() => {
            console.log("Updated!");
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