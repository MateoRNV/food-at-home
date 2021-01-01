<template>
  
    <v-card>
      <v-card-title class="justify-center">
        <span class="headline">
          {{ formTitle }}
        </span>
      </v-card-title>

      <v-card-text>
        <v-container>
          <v-text-field
            label="Fullname"
            v-model="user.name"
            :error-messages="errors.name"
            outlined
          ></v-text-field>

          <v-row>
            <v-col>
              <v-text-field
                :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                :type="show ? 'text' : 'password'"
                label="Password"
                outlined
                @click:append="show = !show"
                v-model="user.password"
                :error-messages="errors.password"
              ></v-text-field>
            </v-col>
            <v-col>
              <v-text-field
                :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
                :type="show2 ? 'text' : 'password'"
                name="password_confirmation"
                label="Confirm password"
                outlined
                @click:append="show2 = !show2"
                v-model="user.password_confirmation"
                :error-messages="errors.password"
              ></v-text-field>
            </v-col>
          </v-row>

          <v-text-field
            label="Email"
            outlined
            v-model="user.email"
            :error-messages="errors.email"
          ></v-text-field>
          <v-select
            :items="userTypes"
            label="Type of user"
            v-model="user.type"
            outlined
            :error-messages="errors.type"
          ></v-select>
          <v-file-input
            show-size
            outlined
            prepend-inner-icon="mdi-camera"
            prepend-icon=""
            label="Profile photo"
          ></v-file-input>
        </v-container>
      </v-card-text>

      <v-card-actions class="pb-2 pr-2">
        <v-spacer></v-spacer>
        <v-btn color="red" 
          @click.prevent="cancel" 
          text
        >
          Cancel
        </v-btn>
        <v-btn
          v-if="isNew"
          color="green"
          text
          @click.prevent="registerAccount"
        >
          Create account
        </v-btn>
        <v-btn
          v-else
          color="green"
          text
          @click.prevent="updateAccount"
          >
            Update
        </v-btn>
      </v-card-actions>
    </v-card>
</template>


<script>
export default {
  props: ["formTitle", "user", "isNew"],
  data() {
    return {
      userTypes: ["EC", "ED", "EM"],
      show: false,
      show2: false,
      errors: [],
    };
  },
  methods: {
    registerAccount() {
      axios
        .post("/api/users", this.user)
        .then(() => {
          console.log("Registered!");
          this.$toasted.show("Created succed", {
            type: "success",
            duration: 3000,
          });
        })
        .catch((e) => {
          console.log("Oh oh!");
          console.log(e.response.data.errors);
          this.errors = e.response.data.errors;
          this.$toasted.show("Created failed", {
            type: "error",
            duration: 3000,
          });
        });
      this.$emit("dialog", true);
    },
    updateAccount() {
      console.log(this.user.id);
      axios
        .put("/api/users/" + this.user.id, this.user)
        .then(() => {
          console.log("Updated!");
          console.log(this.user);
          this.$toasted.show("Updated succed", {
            type: "success",
            duration: 3000,
          });
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

      this.$emit("dialog", false);
    },
    cancel() {
      this.$emit("dialog", false);
    },
  },
};
</script>

<style>
</style>