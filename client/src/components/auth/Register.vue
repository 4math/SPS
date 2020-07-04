<template>
  <div id="register-form">
    <h1>
      <b>Welcome to the Register Page!</b>
    </h1>

    <b-form action="#" @submit.stop.prevent="register">
      <div class="form-input">
        <b-form-group
          id="username-input-group"
          label="Username: "
          label-for="username-input"
          invalid-feedback="Username is required and should be at least 3 characters long"
          valid-feedback="Looks Good"
        >
          <b-form-input
            id="username-input"
            v-model="$v.name.$model"
            class="username-input"
            :state="validateState('name')"
            placeholder="George"
            required
            type="text"
          />
        </b-form-group>
      </div>

      <div class="form-input">
        <b-form-group
          id="email-input-group"
          label="E-mail: "
          label-for="email-input"
          invalid-feedback="E-mail is required"
        >
          <b-form-input
            id="email-input"
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
          invalid-feedback="Password must be at least 6 characters long"
        >
          <b-form-input
            id="password-input"
            v-model="$v.password.$model"
            class="text-input"
            :state="validateState('password')"
            required
            type="password"
          />
          <b-form-text id="password-help-block">
            Your password must be 6-20 characters long, contain letters and
            numbers, and must not contain spaces, special characters, or emoji.
          </b-form-text>
        </b-form-group>
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
        <b-button class="submit-btn" type="submit">
          Submit
        </b-button>
      </div>
    </b-form>
  </div>
</template>

<script>
import { required, minLength } from "vuelidate/lib/validators";
import { validationMixin } from "vuelidate";

export default {
  name: "Register",
  mixins: [validationMixin],
  data() {
    return {
      name: "",
      email: "",
      password: "",
      error: false,
      errors: [],
    };
  },
  validations: {
    name: {
      type: String,
      required,
      minLength: minLength(3),
    },
    email: {
      type: String,
      required,
    },
    password: {
      type: String,
      required,
      minLength: minLength(6),
    },
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
