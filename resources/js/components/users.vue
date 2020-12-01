<template>
  <v-main class="grey lighten-4">
    <br />
    <!--<div class="title2">User List</div> -->

    <v-container fluid>
      <div class="white rounded-lg px-5 py-5 my-5">
        <div class="text-h2 font-weight-bold">
          Users List
          <div class="float-right">
          <v-btn color="primary" dark class="mb-2" v-bind="attrs" v-on="on">
            New User
          </v-btn>
          </div>
        </div>
        <br />
        <v-card>
          <v-card-title>
            <v-spacer></v-spacer>
            <v-spacer></v-spacer>
            <v-spacer></v-spacer>
            <v-text-field
              v-model="search"
              append-icon="mdi-magnify"
              label="Search"
              single-line
              hide-details
            ></v-text-field>
          </v-card-title>
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
                :src="`/storage/fotos/${item.photo_url}`"
                max-height="90"
                max-width="80"
                position="center"
                sizes=""
              ></v-img>
            </template>
          </v-data-table>
        </v-card>
      </div>
    </v-container>
  </v-main>
</template>

<script>
export default {
  props: ["users"],
  data: function () {
    return {
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