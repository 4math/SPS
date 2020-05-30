<template>
  <div id="charts">
    <h1>Welcome to the Charts tab!</h1>
    <ChartComponent ref="chart" />
  </div>
</template>

<script>
import ChartComponent from "@/components/chart/ChartComponent";
import io from "socket.io-client";
import { mapGetters } from "vuex";

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
    const socket = io.connect("ws://localhost:8090", {
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
        this.$refs.chart.addData(data[0]);
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
};
</script>

<style scoped>
#container {
  margin: 0 auto;
  padding: 0 20px;
  width: 100%;
  box-sizing: border-box;
}

#charts {
  max-width: 90rem;
  margin: 0 auto;
  padding: 0 20px;
  width: 100%;
  box-sizing: border-box;
}
</style>
