<template>
  <div id="app">
    <h1>Welcome to the dashboard, {{ getProfile.name }}!</h1>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import { USER_REQUEST } from "@/store/actions/user";
import store from "@/store";
import router from "@/router/router";

export default {
    name: "Dashboard",
    data() {
        return {};
    },
    computed: {
        ...mapGetters(["getProfile"])
    },
    methods: {},
    beforeRouteEnter(to, from, next) {
        if (store.getters.isAuthenticated) {
            store
                .dispatch(USER_REQUEST)
                .then(() => {
                    next();
                })
                .catch(() => {
                    router.push("/login").catch(console.error);
                });
        }
    }
};
</script>

<style>
#app {
    font-family: Avenir, Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    color: #2c3e50;
    margin-top: 60px;
}
</style>
