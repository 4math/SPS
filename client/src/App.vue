<template>
    <div id="app">
        <Navigator />
        <div class="main-container">
            <router-view />
        </div>
    </div>
</template>

<script>
// import { AUTH_LOGOUT } from "./store/actions/auth";
import { USER_REQUEST } from "./store/actions/user";

import Navigator from "@/components/Navigator";

export default {
    name: "App",
    components: {
        Navigator
    },
    created() {
        // axios.interceptors.response.use(undefined, err => {
        //     return new Promise((resolve, reject) => {
        //         if (
        //             err.status === 401 &&
        //             err.config &&
        //             !err.config.__isRetryRequest
        //         ) {
        //             // if you ever get an unauthorized, logout the user
        //             this.$store.dispatch(AUTH_LOGOUT);
        //             // you can also redirect to /login if needed !
        //             resolve();
        //         }
        //         reject();
        //         throw err;
        //     });
        // });
        if (this.$store.getters.isAuthenticated) {
            this.$store.dispatch(USER_REQUEST);
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
    margin-top: 0 !important;
}

body {
    margin: 0;
}


</style>
