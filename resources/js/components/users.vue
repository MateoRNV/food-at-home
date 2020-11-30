<template>
<v-main class="grey lighten-4">
    <br>
    <!--<div class="title2">User List</div> -->
    
    <v-container fluid>
        
        <div class="white rounded-lg px-5 py-5 my-5">
            <div class="text-h2  font-weight-bold">List Users</div>
            <br>
            <v-card>
                <v-card-title>
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
                    class="elevation-1"
                >
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
      headers:[
      {
        text: 'Name',
        align: 'start',
        value: 'name',
        groupable: false
      },
      {text: 'Email', value: 'email', groupable: false},
      {text: 'Type', value: 'type'},
      {text: 'Photo', value: 'photo_url', groupable: false}          
      ],
      
    }
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
  .title2{
    margin-left: 10%;
    font-size: 40px;
    margin-bottom: -2%;
    font-weight: bold;
  }
</style>