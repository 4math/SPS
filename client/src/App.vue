<template>
  <div id="app">
    <Navigator />
    <Loading :active.sync="isLoading" />
    <!-- <Loading :isLoading=true /> -->
    <div id="app-container">
      <router-view />
    </div>
  </div>
</template>

<script>
import Navigator from "@/components/Navigator";
import { mapGetters } from "vuex";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

export default {
  name: "App",
  components: {
    Navigator,
    Loading,
  },
  data() {
    return {};
  },
  computed: {
    ...mapGetters(["isLoading"]),
  },
  created() {},
};
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 0 !important;
  height: calc(100%) !important;
}

html, body {
  height: 100% !important;
  width: 100%;
  margin: 0;
}

#app-container {
  height: calc(100% - 20px) !important;
}
</style>
