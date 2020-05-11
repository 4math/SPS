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
      @commitDeletion="commitDeletion"
    />

    <b-button id="add-device" variant="primary" @click="showModal = !showModal">
      +
    </b-button>

    <DeviceRegister
      :show="showModal"
      @closeModal="showModal = !showModal"
      @registerSocket="registerSocket"
    />
    <Confirm
      :show-confirm="showConfirm"
      @closeConfirm="showConfirm = !showConfirm"
      @confirm="confirmToDelete"
    />
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import { USER_REQUEST } from "@/store/actions/user";
import store from "@/store";
import Socket from "@/components/Socket.vue";
import DeviceRegister from "@/components/DeviceRegister.vue";
import Confirm from "@/components/Confirm.vue";
import SocketData from "@/objects/Socket.js";

export default {
  name: "Dashboard",
  components: {
    Socket,
    DeviceRegister,
    Confirm,
  },
  data() {
    return {
      sockets: [],
      showModal: false,
      showConfirm: false,
      idToDelete: -1,
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

    async registerSocket(name, description, socketID) {
      try {
        const { data } = await this.axios.put("/sockets/connect", {
          name: name,
          description: description,
          unique_id: socketID,
        });
        this.sockets.push(new SocketData(data));
      } catch (err) {
        for (let e in err.errors) {
          this.makeToast(err.errors[e][0]);
        }
      }
    },

    commitDeletion(id) {
      this.showConfirm = true;
      this.idToDelete = id;
    },

    async confirmToDelete() {
      try {
        await this.axios.delete(`/sockets/${this.idToDelete}`);
        const index = this.sockets.findIndex(
          (socket) => socket.id === this.idToDelete
        );
        this.sockets.splice(index, 1);
      } catch (err) {
        console.error(err);
      }
    },

    makeToast(message, variant = "danger") {
      this.$bvToast.toast(message, {
        title: "SPS message",
        toaster: "b-toaster-top-left",
        autoHideDelay: 8000,
        appendToast: true,
        variant: variant,
      });
    },
  },
  beforeRouteEnter(to, from, next) {
    if (store.getters.isAuthenticated) {
      store
        .dispatch(USER_REQUEST)
        .then(() => {
          next();
        })
        .catch((err) => {
          console.error(err);
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

#add-device {
  position: fixed;
  bottom: 20px;
  right: 20px;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  font-size: 1.5em;
}
</style>
