<template>
        <v-form>
                <v-text-field label="Email"

                ></v-text-field>
                <v-text-field 
                        v-model="password"
                        :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                        :rules="[rules.required]"
                        :type="show ? 'text' : 'password'"
                        name="input-10-1"
                        label="Password"
                        @click:append="show = !show"
                ></v-text-field>
        </v-form>
</template>

<script>
export default {
  data: () => ({
    show: false,
    password: 'Password',
    rules: {
        required: value => !!value || 'Required',
    }
  })
};
</script>
