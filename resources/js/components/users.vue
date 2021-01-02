<template>
  <v-main class="grey lighten-4">
    <br />
    <v-container fluid>
      <div class="white rounded-lg px-5 py-5 my-5">
        <div class="text-h2 font-weight-bold">
          Users List
          <div class="float-right">
            <v-dialog persistent v-model="dialogAdd" max-width="500">
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
                @dialog="fecharDialog"
                :user="userEdit"
                :isNew="isNew"
              ></user-addEdit>
            </v-dialog>
            <v-dialog v-model="dialogDelete" max-width="300">
              <v-card>
                <v-card-title>Are you sure?</v-card-title>
                <v-card-text
                  >Please confirm you want to delete
                  {{ userDelete.name }}?</v-card-text
                >
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="red" text @click="closeDelete">Cancel</v-btn>
                  <v-btn color="green" text @click="deleteItem">OK</v-btn>
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

            <template v-slot:item.btnBlock="{ item }">
              <v-btn
                v-if="item.blocked == 0"
                @click="block(item)"
                color="primary"
                dark
              >
                Block</v-btn
              >
              <v-btn
                v-if="item.blocked == 1"
                @click="unblock(item)"
                color="primary"
                dark
              >
                Unblock</v-btn
              >
            </template>

            <template v-slot:item.actions="{ item }">
              <v-icon
                small
                v-if="item.type != 'C'"
                @click="editItemDialog(item)"
                class="mr-2"
              >
                mdi-pencil
              </v-icon>
              <v-icon
                small
                v-if="item.id != $store.state.user.id"
                @click="deleteDialog(item)"
              >
                mdi-delete
              </v-icon>
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
      userDelete: "",
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
        { text: "Disable Users", value: "btnBlock", groupable: false },
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
      (this.userEdit = { name: "", email: "", password: "", type: "" }),
        (this.title = "Add new Employee"),
        (this.dialogAdd = false);
      this.isNew = true;
    },
    editItemDialog(item) {
      /*this.editedIndex = this.desserts.indexOf(item);
      this.editedItem = Object.assign({}, item);*/
      console.log(this.$store.state.user);
      this.title = "Edit User";
      this.isNew = false;
      this.userEdit = item;
      this.dialogAdd = true;
    },
    fecharDialog(isSuccess) {
      this.dialogAdd = false;
    },
    deleteDialog(item) {
      (this.dialogDelete = true), (this.userDelete = item);
    },
    closeDelete() {
      this.dialogDelete = false;
    },
    deleteItem() {
      axios
        .delete("api/users/" + this.userDelete.id)
        .then((response) => {
          this.dialogDelete = false;
          this.$toasted.show("User deleted", { type: "success" });
        })
        .catch((e) => {
          this.$toasted.show("There was a problem, please try again", {
            type: "error",
          });
        });
    },
    block(item) {
      console.log(item.id);
      axios
        .post("api/user/" + item.id + "/block")
        .then((response) => {
          this.dialogDelete = false;
          this.$toasted.show("User blocked", { type: "success" });
        })
        .catch((e) => {
          this.$toasted.show("There was a problem, please try again", {
            type: "error",
          });
        });
    },
    unblock(item) {
      console.log(item.id);
      axios
        .post("api/user/" + item.id + "/unblock")
        .then((response) => {
          this.dialogDelete = false;
          this.$toasted.show("User unblocked", { type: "success" });
        })
        .catch((e) => {
          this.$toasted.show("There was a problem, please try again", {
            type: "error",
          });
        });
    },
  },
};
</script>

<style>
</style>