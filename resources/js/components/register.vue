<template>
  <v-main>
    <v-row class="justify-center">
      <v-col md="6" class="white rounded-lg p-5">
        <div class="text-h5 text-center mb-4 font-weight-bold">
          Create an account
        </div>
        <v-form>
          <div class="d-flex flex-wrap flex-row justify-space-between">
            <div>
              <v-text-field label="Fullname"></v-text-field>
              <v-text-field label="Email"></v-text-field>
              <v-text-field
                :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                :rules="[rules.required]"
                :type="show ? 'text' : 'password'"
                name="input-10-1"
                label="Password"
                @click:append="show = !show"
              ></v-text-field>
            </div>
            <div>
              <v-text-field label="Address"></v-text-field>
              <v-text-field label="Phone"></v-text-field>
              <v-text-field label="NIF"></v-text-field>
            </div>
          </div>
          <v-file-input
            show-size
            prepend-icon="mdi-camera"
            label="Profile photo"
          ></v-file-input>
          <v-row align="center" justify="center"
            >
            <v-btn color="error" class="mr-4">Reset form</v-btn>
            <v-btn color="grey darken-4 white--text" class="mr-4"
              >Create account</v-btn
            >
          </v-row>
        </v-form>
      </v-col>
    </v-row>
  </v-main>
</template>

<script>
export default {
  data: () => ({
    show: false,
    rules: {
      required: (value) => !!value || "Required",
    },
  }),
};
</script>