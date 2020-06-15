<template>
  <div id="app">
    <h1>Welcome to the dashboard, {{ getProfile.name }}!</h1>
    <Socket
      v-for="socket in sockets"
      :id="parseInt(socket.unique_id, 10)"
      :key="parseInt(socket.unique_id, 10)"
      v-bind="socket"
      :title="socket.name"
      :description="socket.description"
      :state="socket.switch_state"
      :momentum-value="socket.momentumValue"
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
import io from "socket.io-client";

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
      // momentumValue: -1,
    };
  },
  computed: {
    ...mapGetters(["getProfile"]),
  },
  mounted() {
    // eslint-disable-next-line no-undef
    this.socket = io.connect(process.env.VUE_APP_WS_URL, {
      transports: ["websocket"],
      upgrade: false,
      query: `userid=${this.getProfile.id}`,
    });

    this.socket.on("connect", () => {
      console.log("CONNECTED");

      this.socket.on("messages.new", (data) => {
        console.log("NEW PRIVATE MESSAGE", data);
        if (this.sockets.length !== 0) {
          this.sockets.find(
            (socket) =>
              parseInt(socket.unique_id, 10) === parseInt(data.socketId, 10)
          ).momentumValue = data.data < 0 ? -1 : data.data;
        }
      });

      this.socket.on("disconnect", () => {
        console.log("DISCONNECTING");
        this.socket.disconnect();
      });

      // Kick it off
      // Can be any channel. For private channels, Laravel should pass it upon page load (or given by another user).
      // eslint-disable-next-line no-undef
      const channel = process.env.VUE_APP_WS_REDIS_CHANNEL;
      this.socket.emit("subscribe-to-channel", { channel: channel });
    });
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
        this.makeToast(`${data.name} has been added`, "success");
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
        this.makeToast(`Socket has been successfully deleted`, "success");
      } catch (err) {
        console.error(err);
      }
    },

    makeToast(message, variant = "danger") {
      this.$bvToast.toast(message, {
        title: "SPS message",
        toaster: "b-toaster-bottom-right",
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
  beforeRouteLeave: function(to, from, next) {
    this.socket.emit("disconnect");
    next();
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
  height: calc(100% - 102px) !important;
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
