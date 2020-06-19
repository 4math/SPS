<template>
  <div id="chart-container">
    <ChartComponent
      ref="chart"
      :selected="selected"
      @onSelectedChange="(option) => (selected = option)"
    />
  </div>
</template>

<script>
import ChartComponent from "@/components/chart/ChartComponent";
import io from "socket.io-client";
import { mapGetters } from "vuex";
import { USER_REQUEST } from "@/store/actions/user";
import store from "@/store";
import { SCALE_OPTIONS } from "@/consts";

export default {
  name: "ChartsPage",
  components: {
    ChartComponent,
  },
  data() {
    return {
      socket: null,
      selected: SCALE_OPTIONS.REALTIME,
    };
  },
  computed: {
    ...mapGetters(["getProfile"]),
  },
  watch: {
    selected: function(option) {
      this.switchScale(option);
    },
  },
  mounted() {
    this.switchScale(this.selected);
  },
  methods: {
    switchScale(option) {
      switch (option) {
        case SCALE_OPTIONS.REALTIME:
          this.runWebsocketConnection();
          break;
        case SCALE_OPTIONS.ONEHOUR:
          this.cleanUp();
          this.runOneHourStats();
          break;
        case SCALE_OPTIONS.ONEDAY:
          this.cleanUp();
          this.runOneDayStats();
          break;
        case SCALE_OPTIONS.ONEWEEK:
          this.cleanUp();
          this.runOneWeekStats();
          break;
      }
    },

    runWebsocketConnection() {
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
          if (this.$refs.chart) {
            this.$refs.chart.addData(
              data.data,
              parseInt(data.socketId),
              data.timestamp
            );
          }
        });

        this.socket.on("disconnect", () => {
          console.log("DISCONNECTING");
          this.socket.disconnect();
        });

        const channel = "test";

        this.socket.emit("subscribe-to-channel", { channel: channel });
        console.log(`SUBSCRIBED TO <${channel}>`);
      });
    },

    runOneHourStats() {
      this.$refs.chart.clearChart();
      console.log("Onehourstats!");
    },

    runOneDayStats() {
      console.log("Onedaystats!");
    },

    runOneWeekStats() {
      console.log("Oneweekstats!");
    },

    wsDisconnect() {
      if (this.socket) {
        this.socket.emit("disconnect");
      }
      this.socket = null;
    },

    cleanUp() {
      this.$refs.chart.clearChart();
      this.wsDisconnect();
    },
  },
  beforeRouteEnter: function(to, from, next) {
    if (store.getters.isAuthenticated) {
      store
        .dispatch(USER_REQUEST)
        .then(() => {
          next((vm) => {
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
    this.wsDisconnect();
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
