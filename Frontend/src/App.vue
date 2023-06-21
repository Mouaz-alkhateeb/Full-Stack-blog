<template>
  <v-app id="inspire">
    <navbar-component />
    <v-main class="bg-grey-lighten-3">
      <v-container>
        <v-row>
          <v-col>
            <v-sheet min-height="70vh" rounded="lg">
              <router-view />
            </v-sheet>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import NavbarComponent from "@/components/NavbarComponent.vue";
export default {
  components: {
    NavbarComponent,
  },
};
</script>
