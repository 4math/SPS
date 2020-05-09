<template>
  <div id="register-form">
    <h1>
      <b>Welcome to the Register Page!</b>
    </h1>

    <b-form
      action="#"
      @submit.prevent="register"
    >
      <div class="form-input">
        <label for="username">Username:</label>
        <b-input
          id="username"
          v-model="name"
          type="text"
          name="username"
          class="text-input"
          :state="validation"
          placeholder="George"
        />
        <b-form-invalid-feedback
          class="form-feedback"
          :state="validation"
        >
          Your username should be at least 3 characters long.
        </b-form-invalid-feedback>
        <b-form-valid-feedback
          class="form-feedback"
          :state="validation"
        >
          Looks Good.
        </b-form-valid-feedback>
      </div>

      <div class="form-input">
        <label for="email">E-mail:</label>
        <b-input
          id="email"
          v-model="email"
          type="email"
          name="email"
          class="text-input"
          placeholder="example@example.net"
        />
      </div>

      <div class="form-input">
        <label for="password">Password:</label>
        <b-input
          id="password"
          v-model="password"
          type="password"
          name="password"
          class="text-input"
        />
        <b-form-text id="password-help-block">
          Your password must be 6-20 characters long, contain letters and
          numbers, and must not contain spaces, special characters, or emoji.
        </b-form-text>
      </div>

      <div class="form-input">
        <b-alert
          v-for="err in errors"
          :key="err"
          v-model="error"
          variant="danger"
        >
          {{ err }}
        </b-alert>
      </div>

      <div class="form-input">
        <b-button
          class="submit-btn"
          type="submit"
        >
          Submit
        </b-button>
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
      errors: [],
    };
  },
  computed: {
    validation() {
      return this.name.length >= 3;
    },
  },
  methods: {
    register() {
      this.axios
        .post("/auth/register", {
          name: this.name,
          email: this.email,
          password: this.password,
        })
        .then(() => {
          this.$router.push("/login");
        })
        .catch((err) => {
          this.error = true;
          let errArray = Object.values(err.response.data.errors);
          this.errors = Array.prototype.concat(...errArray);
        });
    },
  },
  
};
</script>

<style scoped>
@import "./../../assets/styles/form.css";
</style>
