<template>
  <div class="text-center">
    <h1 class="pt-5">Login Page</h1>
    <div class="px-7 py-4">
      <v-form ref="form" v-model="isValid" lazy-validation>
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
        <v-alert
          prominent
          type="error"
          variant="tonal"
          class="my-2 mx-5"
          v-if="errorMessage"
          >{{ errorMessage }}
        </v-alert>
        <v-btn
          color="success"
          class="mr-4"
          @click.prevent="login"
          :disabled="!isValid"
        >
          Login
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
const email = ref("");
const password = ref("");
const errorMessage = ref("");
const emailRules = ref([
  (v) => !!v || "E-mail is required",
  (v) => /.+@.+\..+/.test(v) || "E-mail must be valid",
]);
const passwordRules = ref([
  (v) => !!v || "password is required",
  (v) => (v && v.length <= 10) || "password must be less than 20 characters",
]);
const reset = () => {
  email.value = "";
  password.value = "";
  emailRules.value = "";
  passwordRules.value = "";
};
const login = () => {
  isLoading.value = true;
  store
    .dispatch("login", {
      email: email.value,
      password: password.value,
    })
    .then(() => {
      isLoading.value = false;
      router.push("/");
    })
    .catch(() => {
      isLoading.value = false;
      errorMessage.value = "User Not Exist..!";
      setTimeout(() => {
        errorMessage.value = "";
        email.value = "";
        password.value = "";
        emailRules.value = "";
        passwordRules.value = "";
      }, 4000);
    });
};
</script>
