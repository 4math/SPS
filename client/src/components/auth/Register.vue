
<template>
    <div id="register-form">
        <h1>
            <b>Welcome to the Register Page!</b>
        </h1>

        <form action="#" @submit.prevent="register">
            <div class="form-input">
                <label for="name">Name:</label>
                <br />
                <input type="text" name="name" id="name" class="text-input" v-model="name" />
            </div>

            <div class="form-input">
                <label for="email">E-mail:</label>
                <br />
                <input type="email" name="email" id="email" class="text-input" v-model="email" />
            </div>

            <div class="form-input">
                <label for="password">Password:</label>
                <br />
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="text-input"
                    v-model="password"
                />
            </div>

            <div class="form-input">
                <button class="submit-btn" type="submit">Submit</button>
            </div>
        </form>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: "Register",
    data() {
        return {
            name: "",
            email: "",
            password: ""
        };
    },
    methods: {
        register() {
            axios.post("/auth/register", {
                name: this.name,
                email: this.email,
                password: this.password
            }).then(() => {
                this.$router.push('/login');
            })
        }
    }
};
</script>

<style>
</style>