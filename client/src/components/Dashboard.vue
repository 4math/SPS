<template>
    <div id="app">
        <h1>Welcome to the dashboard, {{ getProfile.name }}!</h1>
    </div>
</template>

<script>

import { mapGetters } from "vuex";

export default {
    name: "Dashboard",
    data() {
        return {

        }
    },
    methods: {
    },
    computed: {
        ...mapGetters(["getProfile"]),
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
