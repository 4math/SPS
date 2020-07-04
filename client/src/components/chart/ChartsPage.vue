<template>
  <div id="chart-container">
    <ChartComponent
      ref="chart"
      :graph="graph"
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
import { SCALE_OPTIONS, GRAPHS } from "@/consts";
import WeekStatisticsFormatter from "./statistics-formatters/WeekStatisticsFormatter";
import DayStatisticsFormatter from "./statistics-formatters/DayStatisticsFormatter";
import HourStatisticsFormatter from "./statistics-formatters/HourStatisticsFormatter";
import StatisticsFormatter from "./statistics-formatters/StatisticsFormatter";

export default {
  name: "ChartsPage",
  components: {
    ChartComponent,
  },
  data() {
    return {
      socket: null,
      selected: SCALE_OPTIONS.REALTIME,
      graph: GRAPHS.LINE,
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
          this.cleanUp();
          this.graph = GRAPHS.LINE;
          this.$refs.chart.updateTitle("REAL-TIME DATA GRAPH");
          this.runWebsocketConnection();
          break;
        case SCALE_OPTIONS.ONEHOUR:
          this.cleanUp();
          this.graph = GRAPHS.BAR;
          this.$refs.chart.updateTitle("ONE HOUR AVERAGE DATA GRAPH");
          this.runOneHourStats();
          break;
        case SCALE_OPTIONS.ONEDAY:
          this.cleanUp();
          this.graph = GRAPHS.BAR;
          this.$refs.chart.updateTitle("ONE DAY AVERAGE DATA GRAPH");
          this.runOneDayStats();
          break;
        case SCALE_OPTIONS.ONEWEEK:
          this.cleanUp();
          this.graph = GRAPHS.BAR;
          this.$refs.chart.updateTitle("ONE WEEK AVERAGE DATA GRAPH");
          this.runOneWeekStats();
          break;
      }
    },

    runWebsocketConnection() {
      const formatter = new StatisticsFormatter();

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
          const timestamp = formatter.getHoursAndMinutes(data.timestamp);
          this.$refs.chart.pushLabel(timestamp);
          this.$refs.chart.addData({
            unique_id: parseInt(data.socketId),
            data: data.data,
            timestamp: timestamp,
          });
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

    async runOneHourStats() {
      const formatter = new HourStatisticsFormatter();
      const [time_from, time_to] = formatter.createTimeRange();

      let hasGoneOneTime = false;
      try {
        for (let socket of this.getSockets) {
          const { data } = await this.axios.post(
            `/measurements/get-period/${socket.id}`,
            {
              time_from: time_from,
              time_to: time_to,
            }
          );
          let statistics = [];
          formatter.fillStatistics(statistics);

          for (let measurement of data) {
            const date = new Date(measurement.created_at);

            const hour = date.getUTCHours();
            const minutes = date.getUTCMinutes();

            let branch = statistics.filter(
              (time) =>
                time.hour === hour && minutes >= time.from && minutes <= time.to
            );
            if (branch[0]) {
              branch[0].total += measurement.power;
              branch[0].amount++;
            }
          }

          console.log(statistics);

          if (!hasGoneOneTime) {
            for (let piece of statistics) {
              this.$refs.chart.pushLabel(piece.label);
            }
            hasGoneOneTime = true;
          }

          for (let piece of statistics) {
            this.$refs.chart.addData({
              unique_id: parseInt(socket.unique_id),
              data: piece.total === 0 ? 0 : piece.total / piece.amount,
              timestamp: piece.label,
            });
          }
        }
      } catch (err) {
        console.error(err);
      }
    },

    async runOneDayStats() {
      const formatter = new DayStatisticsFormatter();

      const [time_from, time_to] = formatter.createTimeRange();

      let hasGoneOneTime = false;
      try {
        for (let socket of this.getSockets) {
          const { data } = await this.axios.post(
            `/measurements/get-period/${socket.id}`,
            {
              time_from: time_from,
              time_to: time_to,
            }
          );

          let statistics = [];
          formatter.fillStatistics(statistics);

          for (let measurement of data) {
            const date = new Date(measurement.created_at);

            const day = date.getUTCDate();
            const hour = date.getUTCHours();

            let branch = statistics.filter(
              (time) => time.hour === hour && time.day === day
            );
            if (branch[0]) {
              branch[0].total += measurement.power;
              branch[0].amount++;
            }
          }

          if (!hasGoneOneTime) {
            for (let piece of statistics) {
              // if (piece.total === 0) continue;
              this.$refs.chart.pushLabel(piece.label);
            }
            hasGoneOneTime = true;
          }

          for (let piece of statistics) {
            // if (piece.total === 0) continue;

            this.$refs.chart.addData({
              unique_id: parseInt(socket.unique_id),
              data: piece.total === 0 ? 0 : piece.total / piece.amount,
              timestamp: piece.label,
            });
          }
        }
      } catch (err) {
        console.error(err);
      }
    },

    async runOneWeekStats() {
      const formatter = new WeekStatisticsFormatter();
      const [time_from, time_to] = formatter.createTimeRange();

      let hasLoopedOneTime = false;
      try {
        for (let socket of this.getSockets) {
          const { data } = await this.axios.post(
            `/measurements/get-period/${socket.id}`,
            {
              time_from: time_from,
              time_to: time_to,
            }
          );

          const statistics = [];
          formatter.fillStatistics(statistics);

          for (let measurement of data) {
            const date = new Date(measurement.created_at);

            const day = date.getUTCDate();

            let branch = statistics.filter((time) => time.day === day);
            if (branch[0]) {
              branch[0].total += measurement.power;
              branch[0].amount++;
            }
          }

          if (!hasLoopedOneTime) {
            for (let piece of statistics) {
              this.$refs.chart.pushLabel(piece.label);
            }
            hasLoopedOneTime = true;
          }

          for (let piece of statistics) {
            this.$refs.chart.addData({
              unique_id: parseInt(socket.unique_id),
              data: piece.total === 0 ? 0 : piece.total / piece.amount,
              timestamp: piece.label,
            });
          }
        }
      } catch (err) {
        console.error(err);
      }
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
