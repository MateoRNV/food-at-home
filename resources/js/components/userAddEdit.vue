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
          :error-messages="errors.photo_url"
          accept=".jpg,.png,.jpeg"
          @change="onFileChange"
        ></v-file-input>
      </v-container>
    </v-card-text>

    <v-card-actions class="pb-2 pr-2">
      <v-spacer></v-spacer>
      <v-btn color="red" @click.prevent="cancel" text> Cancel </v-btn>
      <v-btn v-if="isNew" color="green" text @click.prevent="uploadPhoto">
        Create account
      </v-btn>
      <v-btn v-else color="green" text @click.prevent="confirmUpdate">
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
      photo_file: "",
    };
  },
  methods: {
    onFileChange() {
      this.photo_file = event.target.files[0];
    },
    confirmUpdate() {
      if (this.photo_file !== "") {
        console.log("update photo");
        this.updatePhoto();
      } else {
        this.user.photo_url = null;
        console.log("not update " + this.user.photo_url);
        this.updateAccount();
      }
    },
    uploadPhoto() {
      let formData = new FormData();
      formData.append("photo_file", this.photo_file);

      axios
        .post("/api/users/photos", formData, {
          headers: { "Content-Type": "multipart/form-data" },
        })
        .then((res) => {
          this.user.photo_url = res.data.filename;
          this.registerAccount();
        })
        .catch((e) => {
          console.log("There was a problem while uploading the photo");
          console.log(e);
        });
    },
    updatePhoto() {
      let formData = new FormData();

      formData.append("photo_file", this.photo_file);

      axios
        .post("/api/users/" + this.user.id+"/photos", formData, {
          headers: { "Content-Type": "multipart/form-data" },
        })
        .then((res) => {
          this.user.photo_url = res.data.filename;
          this.updateAccount();
        })
        .catch((e) => {
          console.log("There was a problem while updating the photo");
        });
    },
    registerAccount() {
      axios
        .post("/api/users", this.user)
        .then((res) => {
          
          this.$store.commit("ADD_USER_TO_LIST", res.data.user);
          this.$store.commit("UPDATE_USER_FROM_LIST", res.data.user);
          this.$toasted.show("Created succed", {
            type: "success",
            
          });
        })
        .catch((e) => {
          console.log(e.response.data.errors);
          this.errors = e.response.data.errors;
          this.$toasted.show("Created failed", {
            type: "error",
            
          });
        });
      this.$emit("dialog", true);
    },
    updateAccount() {
      if (typeof this.user.password === "undefined") {
        this.user.password = null;
      }
      axios
        .put("/api/users/" + this.user.id, this.user)
        .then((res) => {
          
          this.$store.commit("UPDATE_USER_FROM_LIST", res.data.user);
          
          this.$toasted.show("Updated succed", {type: "success",});
        })
        .catch((e) => {
          
          this.errors = e.response.data.errors;
          this.$toasted.show("Updated failed", {
            type: "error",
          });
        });

      this.$emit("dialog");
    },
    cancel() {
      this.$emit("dialog", this.user);
    },
  },
};
</script>

<style>
</style>