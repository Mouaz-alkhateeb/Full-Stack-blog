<template>
  <v-app-bar flat>
    <v-container class="fill-height d-flex align-center">
      <v-avatar class="mr-10 ml-4" color="grey-darken-1" size="32"></v-avatar>

      <v-btn to="/" variant="text"> Home </v-btn>
      <v-btn to="/posts" v-if="get_loggedIn" variant="text">My Posts </v-btn>
      <v-spacer></v-spacer>
      <v-spacer></v-spacer>
      <v-spacer></v-spacer>

      <v-spacer></v-spacer>
      <v-spacer></v-spacer>

      <div class="text-center">
        <v-menu open-on-hover>
          <template v-slot:activator="{ props }">
              <v-badge color="red" overlap>
                <template v-slot:badge>
                  <span class="badge__text">{{ unread_notification }}</span>
                </template>
                <v-btn color="green" rounded="lg" v-bind="props">
                  <v-icon color="green" class="mx-2">mdi-bell-badge</v-icon>
                </v-btn>
              </v-badge>
          </template>

          <v-list>
            <v-list-item v-for="(item, index) in notifications" :key="index">
              <v-list-item-title>{{ item.message}}</v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>
      </div>

      <v-btn v-if="!get_loggedIn" to="/login" variant="text"> Login </v-btn>
      <v-btn v-if="!get_loggedIn" to="/register" variant="text">
        Register
      </v-btn>
      <v-btn v-if="get_loggedIn" variant="text" @click.prevent="logout()">
        Logout
      </v-btn>

      <v-spacer></v-spacer>

    </v-container>
  </v-app-bar>
</template>

<script>
import axios from 'axios'
import { echo } from "@/main.js";

export default {
  data() {
    return {
      token: null,
      notifications: [],
      unread_notification: null
    };
  },



  mounted() {
    this.checkUserStatus();


    const channel = echo.private(`notifications.${this.user_data.id}`);

    channel.listen("AddCommentEvent", (data) => {
      this.notifications.push(data.data);
      this.unread_notification = data.unread_notifiy
    });
  },




  created() {
  this.fetchNotifications();
},
  methods: {
    checkUserStatus() {
      if (localStorage.getItem("token") != null) {
        this.token = localStorage.getItem("token");
      } else {
        console.log("token has expired")
      }
    },
    logout() {
      this.$store
        .dispatch("logout")
        .then(() => {
          this.$router.push("/login");
        })
        .catch((error) => {
          console.log(error.message);
        });
    },
    fetchNotifications() {
      axios.get('http://127.0.0.1:8000/api/notification', {
        headers: {
          Authorization: `Bearer ${localStorage.token}`
        }
      })
        .then(response => {
          this.notifications = response.data.data;
        this.unread_notification= response.data.unread_notification
      })
      .catch(error => {
        console.log(error.message);
      });
    }
  },
  computed: {
    get_loggedIn() {
      return this.$store.getters.get_loggedIn;
    },
    user_data() {
      return this.$store.getters.get_user;
    },
  },
  created() {
    this.fetchNotifications();
  }
};
</script>
