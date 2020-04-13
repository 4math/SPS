<template>
    <div id="login-form">
        <h1 id="title">
            <b>Welcome to the Login Page!</b>
        </h1>

        <b-form @submit.prevent="login" class="login">
            <div class="form-input">
                <label for="email">E-mail:</label>
                <b-input
                    type="email"
                    name="email"
                    id="email"
                    class="text-input"
                    v-model="email"
                    placeholder="example@example.net"
                ></b-input>
            </div>

            <div class="form-input">
                <label for="password">Password:</label>
                <b-input
                    type="password"
                    name="password"
                    id="password"
                    class="text-input"
                    v-model="password"
                ></b-input>
            </div>

            <div class="form-input">
                <b-alert
                    v-model="error"
                    variant="danger"
                >{{ errMsg }}</b-alert>
            </div>

            <div class="form-input">
                <b-button class="submit-btn" type="submit">Submit</b-button>
            </div>
        </b-form>
    </div>
</template>

<script>
import { AUTH_REQUEST } from "@/store/actions/auth.js";

export default {
    name: "Login",
    data() {
        return {
            email: "",
            password: "",
            error: false,
            errMsg: ""
        };
    },
    methods: {
        login() {
            this.$store
                .dispatch(AUTH_REQUEST, {
                    email: this.email,
                    password: this.password
                })
                .then(() => {
                    this.$router.push("/dash");
                })
                .catch(err => {
                    this.error = true;
                    // let errArray = Object.values(err.response.data.error);
                    // this.errors = Array.prototype.concat(...errArray);
                    this.errMsg = err.response.data.error;
                });
        }
    },
};
</script>

<style scoped>
@import "./../../assets/styles/form.css";
</style>
