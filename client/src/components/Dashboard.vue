<template>
  <div id="app">
    <h1>Welcome to the dashboard, {{ getProfile.name }}!</h1>
    <Socket
      v-for="socket in sockets"
      :key="socket.id"
      v-bind="socket"
      :title="socket.name"
      :description="socket.description"
      :state="socket.switch_state"
      @put="put"
    />
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import { USER_REQUEST } from "@/store/actions/user";
import store from "@/store";
import router from "@/router/router";
import Socket from "@/components/Socket.vue";
import SocketData from "@/objects/Socket.js";

export default {
  name: "Dashboard",
  components: {
    Socket,
  },
  data() {
    return {
      sockets: [],
    };
  },
  computed: {
    ...mapGetters(["getProfile"]),
  },
  created() {
    this.axios
      .get("/sockets/list")
      .then((response) => {
        response.data.forEach((data) => {
          this.sockets.push(new SocketData(data));
        });
      })
      .catch(console.error);
  },
  methods: {
    async put(id, value) {
      try {
        await this.axios.put(`/sockets/${id}`, { state: +value });
        this.sockets.find((socket) => socket.id === id).switch_state = value;
      } catch (err) {
        console.error(err);
      }
    },
  },
  beforeRouteEnter(to, from, next) {
    if (store.getters.isAuthenticated) {
      store
        .dispatch(USER_REQUEST)
        .then(() => {
          next();
        })
        .catch(() => {
          router.push("/login").catch(console.error);
        });
    }
  },
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
