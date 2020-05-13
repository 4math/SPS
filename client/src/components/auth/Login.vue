<template>
  <div id="login-form">
    <h1 id="title">
      <b>Welcome to the Login Page!</b>
    </h1>

    <b-form class="login" @submit.stop.prevent="login">
      <div class="form-input">
        <b-form-group
          id="email-input-group"
          label="E-mail: "
          label-for="email-input"
          invalid-feedback="E-mail is required"
        >
          <b-form-input
            id="email"
            v-model="$v.email.$model"
            class="text-input"
            :state="validateState('email')"
            placeholder="example@example.net"
            required
            type="email"
          />
        </b-form-group>
      </div>

      <div class="form-input">
        <b-form-group
          id="password-input-group"
          label="Password: "
          label-for="password-input"
          invalid-feedback="Password is required"
        >
          <b-form-input
            id="password"
            v-model="$v.password.$model"
            class="text-input"
            :state="validateState('password')"
            required
            type="password"
          />
        </b-form-group>
      </div>

      <div class="form-input">
        <b-alert v-model="error" variant="danger">
          {{ errMsg }}
        </b-alert>
      </div>

      <div class="form-input">
        <b-button class="submit-btn" type="submit">
          Submit
        </b-button>
      </div>
    </b-form>
  </div>
</template>

<script>
import { AUTH_REQUEST } from "@/store/actions/auth.js";
import { required } from "vuelidate/lib/validators";
import { validationMixin } from "vuelidate";

export default {
  name: "Login",
  mixins: [validationMixin],
  data() {
    return {
      email: "",
      password: "",
      error: false,
      errMsg: "",
    };
  },
  validations: {
    email: {
      type: String,
      required,
    },
    password: {
      type: String,
      required,
    },
  },
  methods: {
    login() {
      this.$store
        .dispatch(AUTH_REQUEST, {
          email: this.email,
          password: this.password,
        })
        .then(() => {
          this.$router.push("/dash");
        })
        .catch((err) => {
          this.error = true;
          // let errArray = Object.values(err.response.data.error);
          // this.errors = Array.prototype.concat(...errArray);
          this.errMsg = err.error;
        });
    },
    validateState(name) {
      const { $dirty, $error } = this.$v[name];
      return $dirty ? !$error : null;
    },
  },
};
</script>

<style scoped>
@import "./../../assets/styles/form.css";
</style>
