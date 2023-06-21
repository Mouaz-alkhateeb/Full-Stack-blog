<template>
  <div class="text-center">
    <v-progress-circular
      v-if="loader_work"
      :size="70"
      :width="3"
      indeterminate
      color="primary"
    ></v-progress-circular>
  </div>
  <div class="text-center">
    <h1>My Posts</h1>
  </div>
  <v-row class="px-3 py-5">
    <v-card
      class="ma-5"
      max-width="350"
      v-for="(post, index) in getCurrentUserPosts"
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
      <v-card-actions class="justify-center">
        <v-btn
          color="green"
          rounded="lg"
          :to="{
            name: 'PostDetails',
            params: {
              id: post.id,
            },
          }"
          >show more <v-icon color="green" class="mx-2">mdi-eye</v-icon>
        </v-btn>
        <v-btn
          v-if="get_loggedIn"
          color="red"
          rounded="lg"
          @click.prevent="deletePost(post.id)"
        >
          <v-icon color="red"> mdi-delete-sweep</v-icon>
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-row>
</template>
<script setup>
import { computed, onMounted, ref } from "vue";
import { useStore } from "vuex";
import axios from "axios";

const store = useStore();
const loader_work = ref(false);

onMounted(() => {
  const getCurrentUserPosts = store.dispatch("getCurrentUserPosts");
  return {
    getCurrentUserPosts,
  };
});
const get_loggedIn = computed(() => {
  return store.getters.get_loggedIn;
});
const getCurrentUserPosts = computed(() => {
  return store.getters.getCurrentUserPosts;
});
const getToken = computed(() => {
  return store.getters.getToken;
});
const getApiURL = computed(() => {
  return store.getters.getApiURL;
});
const deletePost = async (post_id) => {
  let headers = {
    accept: "application/json",
    authorization: "Bearer " + getToken.value,
    "Content-Type": "multipart/form-data",
  };
  let data = {
    id: post_id,
  };

  loader_work.value = true;
  return new Promise((resolve, reject) => {
    axios
      .post(getApiURL.value + "/post/delete", data, {
        headers: headers,
      })
      .then((res) => {
        loader_work.value = true;
        setTimeout(() => {
          // location.reload();
        }, 1000);
        resolve(res);
      })
      .catch((error) => {
        loader_work.value = false;

        reject(error);
      });
  });
};
</script>

