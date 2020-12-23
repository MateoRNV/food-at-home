
<template>
  <v-main class="grey lighten-4">
    <br />
    <v-container fluid>
      <div class="white rounded-lg px-5 py-5 my-5">
        <div class="text-h2 font-weight-bold">
          Users List
          <div class="float-right">
            <v-dialog v-model="dialogAdd" max-width="500px">
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  color="primary"
                  dark
                  class="mb-2"
                  v-bind="attrs"
                  v-on="on"
                  @click="addUser"
                >
                  Add User
                </v-btn>
              </template>
              <user-addEdit
                :formTitle="title"
                @registered="fecharDialog"
                :user="userEdit"
                :isNew="isNew"
              ></user-addEdit>
            </v-dialog>
            <v-dialog v-model="dialogDelete" max-width="500px">
              <v-card>
                <v-card-title class="headline"
                  >Are you sure you want to delete this item?</v-card-title
                >
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1" text @click="closeDelete"
                    >Cancel</v-btn
                  >
                  <v-btn color="blue darken-1" text @click="deleteItem"
                    >OK</v-btn
                  >
                  <v-spacer></v-spacer>
                </v-card-actions>
              </v-card>
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
            :items="$store.state.users"
            :search="search"
            show-group-by
            multi-sort
            class="elevation-1"
          >
            <template v-slot:item.photo_url="{ item }">
              <v-img
                class="border-card"
                :src="`${
                  item.photo_url != null
                    ? '/storage/fotos/' + item.photo_url
                    : '/storage/static/user/default_user_photo.png'
                }`"
                max-width="80"
                position="center"
              ></v-img>
            </template>

            <template v-slot:item.actions="{ item }">
              <v-icon small @click="editItemDialog(item)" class="mr-2">
                mdi-pencil
              </v-icon>
              <v-icon small @click="deleteDialog(item)"> mdi-delete </v-icon>
            </template>
          </v-data-table>
        </v-main>
      </div>
    </v-container>
  </v-main>
</template>

<script>
import UserAddEditComponent from "./userAddEdit";
export default {
  components: {
    "user-addEdit": UserAddEditComponent,
  },
  data: function () {
    return {
      dialogAdd: false,
      dialogDelete: false,
      userDelete: null,
      userEdit: "",
      isNew: null,
      title: null,
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
        {
          text: "Actions",
          value: "actions",
          groupable: false,
          sortable: false,
        },
      ],
    };
  },
  methods: {
    addUser() {
      (this.title = "Add new Employee"), (this.dialogAdd = false);
      this.isNew = true
    },
    editItemDialog(item) {
      /*this.editedIndex = this.desserts.indexOf(item);
      this.editedItem = Object.assign({}, item);*/
      this.title = "Edit User";
      this.isNew = false;
      this.userEdit = item;
      this.dialogAdd = true;
    },
    fecharDialog(isFechado) {
      this.dialogAdd = isFechado;
    },
    deleteDialog(item) {
      (this.dialogDelete = true),
        (this.userDelete = item),
        console.log(this.userDelete);
    },
    closeDelete() {
      this.dialogDelete = false;
    },
    deleteItem() {
      console.log("Item eliminado " + this.userDelete.id);
      axios
        .delete("api/users/" + this.userDelete.id)
        .then((response) => {
          console.log("User deleted " + this.userDelete.id);
          this.dialogDelete = false;
          this.$toasted.show("Delete succes", { type: "success" });
        })
        .catch((e) => {
          console.log("Oh oh!");
          this.$toasted
            .show("There was a problem, please try again", { type: "error" })
            .goAway(3000);
        });
    },
  },
  mounted() {},
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