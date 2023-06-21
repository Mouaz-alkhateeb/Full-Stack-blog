<template>
  <div class="text-center">
    <h1 class="pt-5">Register Page</h1>
    <div class="px-7 py-4">
      <v-form ref="form" v-model="isValid" lazy-validation>
        <v-text-field
          v-model="name"
          :rules="nameRules"
          label="Name"
          required
        ></v-text-field>
        <v-text-field
          v-model="email"
          :rules="emailRules"
          label="E-mail"
          required
        ></v-text-field>
        <v-text-field
          v-model="password"
          :counter="10"
          :rules="passwordRules"
          label="Password"
          type="password"
          required
        ></v-text-field>

        <v-btn
          :disabled="!isValid"
          color="success"
          class="mr-4"
          @click.prevent="register"
        >
          Register
        </v-btn>

        <v-btn color="error" class="mr-4" @click="reset"> Reset Form </v-btn>
      </v-form>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";

const store = useStore();
const router = useRouter();
const isLoading = ref(false);
const isValid = ref(false);
const name = ref("");
const email = ref("");
const password = ref("");
const nameRules = ref([
  (v) => !!v || "Name is required",
  (v) => (v && v.length <= 20) || "Name must be less than 10 characters",
]);
const emailRules = ref([
  (v) => !!v || "E-mail is required",
  (v) => /.+@.+\..+/.test(v) || "E-mail must be valid",
]);
const passwordRules = ref([
  (v) => !!v || "password is required",
  (v) => (v && v.length <= 10) || "password must be less than 20 characters",
]);
const reset = () => {
  name.value = "";
  email.value = "";
  password.value = "";
  nameRules.value = "";
  emailRules.value = "";
  passwordRules.value = "";
};
const register = () => {
  isLoading.value = true;
  store
    .dispatch("register", {
      name: name.value,
      email: email.value,
      password: password.value,
    })
    .then(() => {
      isLoading.value = false;
      router.push("/");
    })
    .catch((error) => {
      isLoading.value = false;
    });
};
</script>

