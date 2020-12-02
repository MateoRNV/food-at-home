
<template>
  <v-main class="grey lighten-4">
    <br >
    <v-container fluid>
      <div class="white rounded-lg px-5 py-5 my-5">
        <div class="text-h2 font-weight-bold">
          Users List
          <div class="float-right">
              <v-dialog v-model="dialog" max-width="500px">
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  color="primary"
                  dark
                  class="mb-2"
                  v-bind="attrs"
                  v-on="on"
                >
                  Add User
                </v-btn>
              </template>
              <user-edit></user-edit>
            </v-dialog>
          </div>
        </div>
        <v-main>

          <v-text-field
            v-model="search"
            append-icon="mdi-magnify"
            label="Search"
            single-line
            hide-details
          ></v-text-field>
          <v-data-table
            :headers="headers"
            :items="users"
            :search="search"
            show-group-by
            multi-sort
            class="elevation-1"
          >
            <template v-slot:item.photo_url="{ item }">
              <v-img
                class="border-card"
                :src="`${item.photo_url != null ? '/storage/fotos/' + item.photo_url : '/storage/static/user/default_user_photo.png'}`"
                max-width="80"
                position="center"
              ></v-img>
            </template>
          </v-data-table>
        </v-main>
      </div>
    </v-container>
  </v-main>
</template>

<script>
import UserEditComponent from "./userEdit";
export default {
  components: {
    "user-edit": UserEditComponent,
  },
  props: ["users"],
  data: function () {
    return {
      dialog: false,
      search: "",
      headers: [
        {
          text: "Photo",
          value: "photo_url",
          groupable: false,
          sortable: false,
        },
        { text: "Id", value: "id", groupable: false },
        { text: "Name", value: "name", groupable: false },
        { text: "Email", value: "email", groupable: false },
        { text: "Type", value: "type" },
      ],
      editedItem: {
        name: "",
      },
    };
  },
  methods: {
    getUsers() {
      axios.get("api/users").then((response) => {
        this.$root.users = response.data.data;
        this.users = this.$root.users;
      });
    },
  },
  mounted() {
    this.getUsers();
  },
};
</script>

<style>
.border-card {
  border-radius: 50%;
  min-height: 70px;
  min-width: 70px;
  margin-top: 5%;
  margin-bottom: 5%;
}
</style>