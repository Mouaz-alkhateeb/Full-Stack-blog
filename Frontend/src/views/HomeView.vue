<template>
  <div class="text-center">
    <h1>
      All Posts
      <v-btn
        v-if="get_loggedIn"
        variant="outlined"
        rounded="xl"
        class="ml-3"
        color="blue"
        @click="open"
        >Add Post</v-btn
      >
    </h1>
  </div>

  <v-row class="px-3 py-5">
    <v-card
      class="ma-5"
      max-width="350"
      v-for="(post, index) in getAllPosts"
      :key="index"
    >
      <v-img
        class="align-end text-white"
        height="150"
        :src="'data:image/jpeg;base64,' + post.file_data"
        cover
      >
        <v-card-title>{{ post.title }}</v-card-title>
      </v-img>
      <v-card-text>
        <div>{{ post.content }}</div>
      </v-card-text>
      <v-card-actions class="justify-center"> </v-card-actions>
    </v-card>
  </v-row>

  <v-dialog v-model="dialog" max-width="600px">
    <v-card>
      <div class="text-center">
        <v-progress-circular
          v-if="loader_work"
          :size="70"
          :width="3"
          indeterminate
          color="primary"
        ></v-progress-circular>
      </div>
      <v-card-title>
        <span class="text-h5">Add A New Post</span>
      </v-card-title>
      <v-card-text>
        <v-form class="px-3" ref="form" v-model="valid" lazy-validation>
          <v-text-field
            v-model="title"
            label=" Title"
            required
            prepend-icon="mdi-rename-box"
            :rules="titleRules"
          ></v-text-field>

          <v-text-field
            label="Content"
            v-model="content"
            required
            :rules="contentRules"
            prepend-icon=" mdi-city"
          ></v-text-field>
          <v-file-input
            id="image"
            ref="fileInput"
            v-model="image"
            label="Upload Image"
            prepend-icon=" mdi-file"
            @input="onSelectFile"
          ></v-file-input>

          <br />
          <v-btn
            v-if="content && title"
            color="blue darken-1"
            class="mr-2"
            text
            @click.prevent="createpost"
            rounded
          >
            Add Now
          </v-btn>
          <v-btn color="red darken-1" text @click.prevent="close" rounded>
            close
          </v-btn>
        </v-form>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>
<script setup>
import { computed, onMounted, ref } from "vue";
import { useStore } from "vuex";
import axios from "axios";
import { useRouter } from "vue-router";

const store = useStore();
const router = useRouter();
const dialog = ref(false);
const loader_work = ref(false);
let valid = ref(true);
const titleRules = ref([
  (v) => !!v || "Title is required",
  (v) => (v && v.length <= 20) || "Title must be less than 20 characters",
]);
const contentRules = ref([(v) => !!v || "Content is required"]);
const content = ref("");
const image = ref("");
const title = ref("");
const open = () => {
  dialog.value = true;
};
const close = () => {
  dialog.value = false;
};
onMounted(() => {
  const posts = store.dispatch("getAllPosts");
  return {
    posts,
  };
});
const get_loggedIn = computed(() => {
  return store.getters.get_loggedIn;
});
const getAllPosts = computed(() => {
  return store.getters.getAllPosts;
});
const getToken = computed(() => {
  return store.getters.getToken;
});
const getApiURL = computed(() => {
  return store.getters.getApiURL;
});
const createpost = async () => {
  let headers = {
    accept: "application/json",
    authorization: "Bearer " + getToken.value,
    "Content-Type": "multipart/form-data",
  };
  let data = {
    title: title.value,
    content: content.value,
    image: image.value,
  };

  if (valid.value) {
    loader_work.value = true;
    return new Promise((resolve, reject) => {
      axios
        .post(getApiURL.value + "/post/create", data, {
          headers: headers,
        })
        .then((res) => {
          loader_work.value = false;

          dialog.value = false;
          router.push({ name: "posts" });
          resolve(res);
        })
        .catch((error) => {
          loader_work.value = false;
          setTimeout(() => {
            dialog.value = false;
          }, 1000);
          reject(error);
        });
    });
  } else {
    dialog.value = false;
  }
};
</script>

<style scoped>
.v-progress-circular {
  margin: 1rem;
}
</style>
