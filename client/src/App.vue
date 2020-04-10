<template>
    <div id="app">
        <!-- <h1>Welcome to the home page!</h1> -->
        <!-- <img alt="Vue logo" src="./assets/logo.png" /> -->
        <Login/>
        <!-- <HelloWorld msg="Welcome to Your Vue.js App" /> -->
    </div>
</template>

<script>
// import HelloWorld from "./components/HelloWorld.vue";
import Login from "./components/auth/Login";

export default {
    name: "App",
    components: {
        // HelloWorld,
        Login
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
