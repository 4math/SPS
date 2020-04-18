<template>
    <div id="app">
        <Navigator />
        <p style="color: red" v-if="isLoading">Loading</p>
        <div class="main-container">
            <router-view />
        </div>
        
    </div>
</template>

<script>
import { USER_REQUEST } from "./store/actions/user";

import Navigator from "@/components/Navigator";
import { mapGetters } from "vuex";

export default {
    name: "App",
    components: {
        Navigator
    },
    data() {
        return {};
    },
    created() {
        if (this.$store.getters.isAuthenticated) {
            this.$store.dispatch(USER_REQUEST);
        }
    },
    computed: {
        ...mapGetters(["isLoading"])
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
    margin-top: 0 !important;
}

body {
    margin: 0;
}
</style>
