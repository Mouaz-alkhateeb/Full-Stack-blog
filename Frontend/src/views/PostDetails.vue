<template>
  <v-card class="auto" max-width="1200">
    <v-img
      class="align-end text-white"
      height="400"
      :src="'data:image/jpeg;base64,' + post.file_data"
      cover
    >
      <v-card-title>{{ post.title }}</v-card-title>
    </v-img>
    <div class="ml-4 mt-5 justify-end">
      <v-btn
        color="blue"
        class="mr-5"
        style="float: right !important"
        @click.prevent="open"
      >
        Update Post</v-btn
      >
    </div>
    <v-card-text>
      <div>{{ post.content }}</div>
    </v-card-text>
    <div class="mx-4">
      <v-row>
        <v-col v-for="card in cards" :key="card" cols="12">
          <v-card>
            <v-list lines="two">
              <v-list-subheader
                >{{ post.comments_count }} Comments
              </v-list-subheader>
              <template v-for="(comment, index) in post.comments" :key="index">
                <v-list-item>
                  <template v-slot:prepend>
                    <v-avatar color="grey-darken-1"></v-avatar>
                  </template>

                  <v-list-item-title>
                    {{ comment.username }}
                    <v-btn
                      v-if="get_loggedIn"
                      color="red"
                      size="xs"
                      rounded
                      class="px-2 justify-center"
                      @click.prevent="deleteComment(comment.id)"
                    >
                      Delete
                    </v-btn>
                  </v-list-item-title>

                  <v-list-item-subtitle>
                    {{ comment.content }}
                  </v-list-item-subtitle>
                </v-list-item>
                <v-divider v-if="n !== 3" :key="`divider-${n}`" inset>
                </v-divider>
              </template>
            </v-list>
          </v-card>
          <v-form ref="form" v-model="valid" lazy-validation
            ><div class="my-2">
              <v-text-field
                v-model="commentContent"
                label="Add Comment"
                required
                :rules="commentRules"
              ></v-text-field>
            </div>
            <v-card-actions>
              <v-btn
                v-if="commentContent"
                color="green"
                @click.prevent="addComment"
              >
                Add Comment</v-btn
              >
            </v-card-actions></v-form
          >
        </v-col>
      </v-row>
    </div>
  </v-card>
  <v-dialog v-model="dialog" max-width="600px">
    <v-card>
      <v-card-title>
        <span class="text-h5">Update Post</span>
      </v-card-title>
      <div class="text-center">
        <v-progress-circular
          v-if="loader_work"
          :size="70"
          :width="3"
          indeterminate
          color="primary"
        ></v-progress-circular>
      </div>
      <v-card-text>
        <v-form class="px-3" ref="form" v-model="valid" lazy-validation>
          <v-text-field
            v-model="post.title"
            label=" Title"
            required
            :rules="titleRules"
            prepend-icon="mdi-rename-box"
          ></v-text-field>

          <v-text-field
            v-model="post.content"
            label="Content"
            required
            :rules="contentRules"
            prepend-icon=" mdi-city"
          ></v-text-field>
          <v-img
            class="align-end text-white"
            height="400"
            :src="'data:image/jpeg;base64,' + post.file_data"
            cover
          ></v-img>
          <v-file-input
            id="image"
            ref="fileInput"
            v-model="post.image"
            label="Upload Image"
            prepend-icon=" mdi-file"
            @input="onSelectFile"
          ></v-file-input>

          <br />
          <v-btn
            color="blue darken-1"
            class="mr-2"
            text
            @click.prevent="updatepost"
            rounded
          >
            update Now
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
import { useRoute } from "vue-router";
import axios from "axios";
const store = useStore();
const route = useRoute();
const post_id = ref("");
const commentContent = ref("");
const dialog = ref(false);
const loader_work = ref(false);
const titleRules = ref([
  (v) => !!v || "Title is required",
  (v) => (v && v.length <= 20) || "Title must be less than 20 characters",
]);
const contentRules = ref([(v) => !!v || "Content is required"]);
const open = () => {
  dialog.value = true;
};
const close = () => {
  dialog.value = false;
};
const cards = ref(["All Comments"]);
post_id.value = route.params.id;
const get_loggedIn = computed(() => {
  return store.getters.get_loggedIn;
});
const getToken = computed(() => {
  return store.getters.getToken;
});
const getApiURL = computed(() => {
  return store.getters.getApiURL;
});
const post = computed(() => {
  return store.state.post;
});
const addComment = async () => {
  let headers = {
    accept: "application/json",
    authorization: "Bearer " + getToken.value,
    "Content-Type": "multipart/form-data",
  };
  let data = {
    post_id: post_id.value,
    content: commentContent.value,
  };
  return new Promise((resolve, reject) => {
    axios
      .post(getApiURL.value + "/post/add_comment", data, {
        headers: headers,
      })
      .then((res) => {
        commentContent.value = "";
        // location.reload();
        resolve(res);
      })
      .catch((error) => {
        reject(error);
      });
  });
};
const deleteComment = async (comment_id) => {
  const headers = {
    accept: "application/json",
    "content-type": "application/json",
    authorization: "Bearer " + getToken.value,
  };
  let data = {
    id: comment_id,
  };
  return new Promise((resolve, reject) => {
    axios
      .post(getApiURL.value + "/post/delete_comment", data, {
        headers: headers,
      })
      .then((res) => {
        location.reload();
        resolve(res);
      })
      .catch((error) => {
        reject(error);
      });
  });
};
const updatepost = async () => {
  const headers = {
    accept: "application/json",
    "Content-Type": "multipart/form-data",
    authorization: "Bearer " + getToken.value,
  };
  let data = {
    id: post_id.value,
    title: post.value.title,
    content: post.value.content,
    image: post.value.image,
  };
  loader_work.value = true;
  return new Promise((resolve, reject) => {
    axios
      .post(getApiURL.value + "/post/update", data, {
        headers: headers,
      })
      .then((res) => {
        loader_work.value = false;
        setTimeout(() => {
          location.reload();
        }, 1000);

        resolve(res);
      })
      .catch((error) => {
        loader_work.value = false;
        reject(error);
      });
  });
};

onMounted(() => {
  const post = ref(store.dispatch("getDetailsPost", post_id.value));
  return {
    post,
  };
});
</script>

