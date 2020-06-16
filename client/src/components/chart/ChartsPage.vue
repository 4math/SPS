<template>
  <div id="chart-container">
    <ChartComponent ref="chart" />
  </div>
</template>

<script>
import ChartComponent from "@/components/chart/ChartComponent";
import io from "socket.io-client";
import { mapGetters } from "vuex";
import { USER_REQUEST } from "@/store/actions/user";
import store from "@/store";

export default {
  name: "ChartsPage",
  components: {
    ChartComponent,
  },
  data() {
    return {
      socket: null,
    };
  },
  computed: {
    ...mapGetters(["getProfile"]),
  },
  mounted() {
    this.socket = io.connect(process.env.VUE_APP_WS_URL, {
      transports: ["websocket"],
      upgrade: false,
      query: `userid=${this.getProfile.id}`,
    });

    this.socket.on("connect", () => {
      console.log("CONNECTED");

      this.socket.on("event", (data) => {
        console.log("EVENT", data);
      });

      this.socket.on("messages.new", (data) => {
        console.log("NEW PRIVATE MESSAGE", data);
        this.$refs.chart.addData(
          data.data,
          parseInt(data.socketId),
          data.timestamp
        );
      });

      this.socket.on("disconnect", () => {
        console.log("DISCONNECTING");
        this.socket.disconnect();
      });

      // Kick it off
      // Can be any channel. For private channels, Laravel should pass it upon page load (or given by another user).
      const channel = "test";

      this.socket.emit("subscribe-to-channel", { channel: channel });
      console.log(`SUBSCRIBED TO <${channel}>`);
    });
  },
  methods: {},
  beforeRouteEnter: function(to, from, next) {
    if (store.getters.isAuthenticated) {
      store
        .dispatch(USER_REQUEST)
        .then(() => {
          next(vm => {
            // Runs after page is loaded
            vm.$refs.chart.turnOffSpecificGraphs();
          });
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

<style scoped>
#chart-container {
  /* max-width: 120rem; */
  margin: 0 auto;
  margin-top: 20px;
  padding: 0 20px;
  width: 100%;
  box-sizing: border-box;
  height: 100% !important;
}
</style>
