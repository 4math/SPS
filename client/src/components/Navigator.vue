<template>
  <div class="navigation">
    <b-navbar toggleable="md" type="dark" variant="dark">
      <b-navbar-brand>
        <router-link class="nav-link" to="/">
          <img src="@/assets/images/logo.png" width="40px">
          <strong>SPS</strong>
        </router-link>
      </b-navbar-brand>

      <b-navbar-toggle target="navbar-toggle-collapse">
        <template v-slot:default="{ expanded }">
          <b-icon v-if="expanded" icon="chevron-bar-up" />
          <b-icon v-else icon="chevron-bar-down" />
        </template>
      </b-navbar-toggle>

      <b-collapse id="navbar-toggle-collapse" is-nav>
        <b-navbar-nav class="ml-auto">
          <b-nav-item v-if="!isAuthenticated && !authLoading">
            <router-link class="nav-link" to="/login">
              <strong>Login</strong>
            </router-link>
          </b-nav-item>

          <b-nav-item v-if="!isAuthenticated && !authLoading">
            <router-link class="nav-link" to="/register">
              <strong>Register</strong>
            </router-link>
          </b-nav-item>

          <b-nav-item v-if="isAuthenticated && !authLoading">
            <router-link class="nav-link" to="/dash">
              <strong>{{ getProfile.name }}</strong>
            </router-link>
          </b-nav-item>

          <b-nav-item v-if="isAuthenticated && !authLoading">
            <router-link class="nav-link" to="/charts">
              <strong>Charts</strong>
            </router-link>
          </b-nav-item>

          <b-nav-item v-if="isAuthenticated || authLoading" @click="logout">
            <span class="nav-link">
              <strong>Logout</strong>
            </span>
          </b-nav-item>
        </b-navbar-nav>
      </b-collapse>
    </b-navbar>
  </div>
</template>

<style scoped>
.nav-link {
  text-decoration: none;
  color: #fff !important;
  user-select: none;
}

.nav-link:hover {
  opacity: 0.9;
}
</style>

<script>
import { mapGetters, mapState } from "vuex";
import { AUTH_LOGOUT } from "@/store/actions/auth";
export default {
  name: "Navigator",
  computed: {
    ...mapGetters(["getProfile", "isAuthenticated"]),
    ...mapState({
      authLoading: (state) => state.auth.status === "loading",
      //   name: state => `${state.user.profile.name}`
    }),
  },
  methods: {
    logout: function() {
      this.$store
        .dispatch(AUTH_LOGOUT)
        .then(() => this.$router.push("/login").catch(console.error));
    },
  },
};
</script>
