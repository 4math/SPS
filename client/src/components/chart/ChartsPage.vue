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
    ...mapGetters(["getSockets"]),
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
              parseInt(data.socketId),
              data.data,
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

    getDate() {
      // const serverTimeOffset = 3;
      const time = new Date();
      const year = time.getFullYear();
      const month =
        time.getMonth() < 10
          ? "0" + (time.getMonth() + 1)
          : time.getMonth() + 1;
      const day = time.getDate() < 10 ? "0" + time.getDate() : time.getDate();
      const hours =
        time.getHours() < 10 ? "0" + time.getHours() : time.getHours();
      const minutes =
        time.getMinutes() < 10 ? "0" + time.getMinutes() : time.getMinutes();
      return `${year}-${month}-${day} ${hours}:${minutes}:00`;
    },

    makeHourLess(time_to) {
      const hourSubStr = {
        start: 11,
        end: 13,
        digits: 2,
      };
      let hour =
        parseInt(time_to.substr(hourSubStr.start, hourSubStr.digits)) - 1;
      hour = hour - 1 < 10 ? "0" + hour : hour;

      const time_from =
        time_to.substr(0, hourSubStr.start) +
        hour +
        time_to.substr(hourSubStr.end);
      return time_from;
    },

    async runOneHourStats() {
      const time_to = this.getDate();
      console.log(time_to);
      const time_from = this.makeHourLess(time_to);
      console.log(time_from);

      try {
        for (let socket of this.getSockets) {
          const { data } = await this.axios.post(
            `/measurements/get-period/${socket.id}`,
            {
              time_from: "2020-06-15 16:00:00",
              time_to: "2020-06-15 17:00:00",
            }
          );
          console.log(data);
          for (let measurement of data) {
            this.$refs.chart.addData(
              parseInt(socket.unique_id),
              measurement.power,
              measurement.created_at
            );
          }
        }
      } catch (err) {
        console.error(err);
      }
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
      this.$refs.chart.turnOffSpecificGraphs();
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
