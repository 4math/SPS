
<template>
    <div id="register-form">
        <h1>
            <b>Welcome to the Register Page!</b>
        </h1>

        <b-form action="#" @submit.prevent="register">
            <div class="form-input">
                <label for="username">Username:</label>
                <b-input
                    type="text"
                    name="username"
                    id="username"
                    class="text-input"
                    v-model="name"
                    :state="validation"
                    placeholder="George"
                ></b-input>
                <b-form-invalid-feedback
                    class="form-feedback"
                    :state="validation"
                >Your username should be at least 3 characters long.</b-form-invalid-feedback>
                <b-form-valid-feedback class="form-feedback" :state="validation">Looks Good.</b-form-valid-feedback>
            </div>

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
                <b-form-text id="password-help-block">
                    Your password must be 6-20 characters long, contain letters and numbers, and must not
                    contain spaces, special characters, or emoji.
                </b-form-text>
            </div>

            <div class="form-input">
                <b-alert
                    v-for="err in errors"
                    :key="err"
                    variant="danger"
                    v-model="error"
                >{{ err }}</b-alert>
            </div>

            <div class="form-input">
                <b-button class="submit-btn" type="submit">Submit</b-button>
            </div>
        </b-form>
    </div>
</template>

<script>
export default {
    name: "Register",
    data() {
        return {
            name: "",
            email: "",
            password: "",
            error: false,
            errors: []
        };
    },
    methods: {
        register() {
            this.axios
                .post("/auth/register", {
                    name: this.name,
                    email: this.email,
                    password: this.password
                })
                .then(() => {
                    this.$router.push("/login");
                })
                .catch(err => {
                    this.error = true;
                    let errArray = Object.values(err.response.data.errors);
                    this.errors = Array.prototype.concat(...errArray);
                });
        }
    },
    computed: {
        validation() {
            return this.name.length >= 3;
        }
    }
};
</script>

<style scoped>
@import "./../../assets/styles/form.css";
</style>