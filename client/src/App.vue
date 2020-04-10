<template>
    <div id="app">
        <!-- <h1>Welcome to the home page!</h1> -->
        <!-- <img alt="Vue logo" src="./assets/logo.png" /> -->
        <div class="main-container">
            <router-view />
        </div>
    </div>
</template>

<script>
// import HelloWorld from "./components/HelloWorld.vue";
// import Login from "./components/auth/Login";
// import axios from "axios";
// import { AUTH_LOGOUT } from "./store/actions/auth";
import { USER_REQUEST } from './store/actions/user';

export default {
    name: "App",
    components: {
        // HelloWorld,
        // Login
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
    margin-top: 60px;
}
</style>
