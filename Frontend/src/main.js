/**
 * main.js
 *
 * Bootstraps Vuetify and other plugins then mounts the App`
 */

// Components
import App from "./App.vue";

// Composables
import { createApp } from "vue";

// Plugins
import { registerPlugins } from "@/plugins";
import router from "./router";
import store from "./store";
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;
const token = localStorage.getItem("token");
const echo = new Echo({
  broadcaster: "pusher",
  key: "local",
  wsHost: "127.0.0.1",
  wsPort: 6001,
  cluster: "mt1",
  forceTLS: false,
  enabledTransports: ["ws", "wss"],
  disableStats: true,
  authEndpoint: "http://127.0.0.1:8000/broadcasting/auth",
  auth: {
    headers: {
      Authorization: `Bearer ${token}`,
      Accept: "application/json",
    },
  },
});

const app = createApp(App);
registerPlugins(app);

app.use(store).use(router).mount("#app");

// Export the echo instance
export { echo };
