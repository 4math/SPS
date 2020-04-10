<template>
    <div id="login-form">
        <h1>
            <b>Welcome to the Login Page!</b>
        </h1>

        <form action="#" @submit.prevent="login">

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
export default {
    name: "Login",
    data() {
        return {
            email: "",
            password: ""
        };
    },
    methods: {
      login() {
        this.$store.dispatch('retrieveToken');
      }
    }
};
</script>

<style>
#login-form {
    font-family: Avenir, Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    color: #2c3e50;
    margin-top: 60px;
}

input {
    border-radius: 5px;
    margin: 10px;
}

label {
    align-content: flex-start;
}
</style>
