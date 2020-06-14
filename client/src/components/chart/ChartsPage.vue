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
    return {};
  },
  computed: {
    ...mapGetters(["getProfile"]),
  },
  mounted() {
    // eslint-disable-next-line no-undef
    const socket = io.connect(process.env.VUE_APP_WS_URL, {
      transports: ["websocket"],
      upgrade: false,
      query: `userid=${this.getProfile.id}`,
    });

    socket.on("connect", () => {
      console.log("CONNECT");

      socket.on("event", function(data) {
        console.log("EVENT", data);
      });

      socket.on("messages.new", (data) => {
        console.log("NEW PRIVATE MESSAGE", data);
        this.$refs.chart.addData(data[0], parseInt(data[1]));  
      });

      socket.on("disconnect", function() {
        console.log("disconnect");
      });

      // Kick it off
      // Can be any channel. For private channels, Laravel should pass it upon page load (or given by another user).
      const channel = "test";

      socket.emit("subscribe-to-channel", { channel: channel });
      console.log(`SUBSCRIBED TO <${channel}>`);
    });
  },
  methods: {},
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

<style scoped>

#chart-container {
  /* max-width: 120rem; */
  margin: 0 auto;
  margin-top: 20px;
  padding: 0 20px;
  width: 100%;
  box-sizing: border-box;
}


</style>
