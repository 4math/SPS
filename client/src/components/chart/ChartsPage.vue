<template>
  <div id="chart-container">
    <ChartComponent
      ref="chart"
      :graph="graph"
      :is-scrolled="isScrolled"
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
      isScrolled: false,
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
          this.isScrolled = false;
          this.runWebsocketConnection();
          break;
        case SCALE_OPTIONS.ONEHOUR:
          this.cleanUp();
          this.graph = GRAPHS.LINE;
          this.isScrolled = true;
          this.runOneHourStats();
          break;
        case SCALE_OPTIONS.ONEDAY:
          this.cleanUp();
          this.graph = GRAPHS.BAR;
          this.runOneDayStats();
          break;
        case SCALE_OPTIONS.ONEWEEK:
          this.cleanUp();
          this.graph = GRAPHS.BAR;
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
            this.$refs.chart.pushLabel(data.timestamp, true);
            this.$refs.chart.addData({
              unique_id: parseInt(data.socketId),
              data: data.data,
              timestamp: data.timestamp,
            });
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
      const time = new Date();
      const magicNumber = -60;
      const offset = new Date().getTimezoneOffset() / magicNumber;
      const year = time.getFullYear();
      const month =
        time.getMonth() < 10
          ? "0" + (time.getMonth() + 1)
          : time.getMonth() + 1;
      const day = time.getDate() < 10 ? "0" + time.getDate() : time.getDate();
      const hours =
        time.getHours() < 10
          ? "0" + (time.getHours() - offset)
          : time.getHours() - offset;
      const minutes =
        time.getMinutes() < 10 ? "0" + time.getMinutes() : time.getMinutes();
      return `${year}-${month}-${day} ${hours}:${minutes}:00`;
    },

    makeTimeByOneLess(time_to, timeSubStr) {
      const digitAmount = 2;
      let hour = parseInt(time_to.substr(timeSubStr.start, digitAmount)) - 1;
      hour = hour - 1 < 10 ? "0" + hour : hour;

      const time_from =
        time_to.substr(0, timeSubStr.start) +
        hour +
        time_to.substr(timeSubStr.end);
      return time_from;
    },

    fitToTimeZone(time) {
      // No comments
      const magicNumber = -60;
      const offset = new Date().getTimezoneOffset() / magicNumber;
      const hourSubStr = {
        start: 11,
        end: 13,
        digits: 2,
      };
      let hour =
        parseInt(time.substr(hourSubStr.start, hourSubStr.digits)) + offset;

      return (
        time.substr(0, hourSubStr.start) + hour + time.substr(hourSubStr.end)
      );
    },

    // Does not work!!!
    async runOneHourStats() {
      const time_to = this.getDate();
      console.log(time_to);
      const time_from = this.makeTimeByOneLess(time_to, {
        start: 11,
        end: 13,
      });
      console.log(time_from);

      try {
        for (let socket of this.getSockets) {
          const { data } = await this.axios.post(
            `/measurements/get-period/${socket.id}`,
            {
              time_from: time_from,
              time_to: time_to,
            }
          );
          console.log(data);
          for (let measurement of data) {
            this.$refs.chart.addData({
              unique_id: parseInt(socket.unique_id),
              data: measurement.power,
              timestamp: measurement.created_at,
            });
          }
        }
      } catch (err) {
        console.error(err);
      }
    },

    async runOneDayStats() {
      const time_to = this.getDate().substr(0, 13) + ":00:00";
      const time_from = this.makeTimeByOneLess(time_to, {
        start: 8,
        end: 10,
      });

      // let hasGoneOneTime = false;
      try {
        for (let socket of this.getSockets) {
          const { data } = await this.axios.post(
            `/measurements/get-period/${socket.id}`,
            {
              time_from: time_from,
              time_to: time_to,
            }
          );

          let information = [];
          // `2020-06-05 17:00:00` take 17 from string
          let hour = parseInt(time_from.substr(11, 13), 10);
          // `2020-06-05 17:00:00` take 05 from string
          let day = parseInt(time_from.substr(8, 10), 10);
          for (let i = 0; i <= 24; ++i) {
            if (hour > 23) {
              hour = 0;
              ++day;
            }

            let label =
              time_from.substr(0, 8) +
              (day < 10 ? "0" + day : day) +
              " " +
              hour +
              ":00:00";

            information.push({
              label: label,
              hour: hour,
              day: day,
              total: 0,
              amount: 0,
            });
            hour++;
          }

          for (let measurement of data) {
            let hour = parseInt(measurement.created_at.substr(11, 13), 10);
            let day = parseInt(measurement.created_at.substr(8, 10), 10);

            let branch = information.filter(
              (time) => time.hour === hour && time.day === day
            );
            if (branch[0]) {
              branch[0].total += measurement.power;
              branch[0].amount++;
            }
          }

          // if (!hasGoneOneTime) {
          for (let piece of information) {
            if (piece.total === 0) continue;
            this.$refs.chart.pushLabel(piece.label, false, false);
          }
          //   hasGoneOneTime = true;
          // }

          for (let piece of information) {
            if (piece.total === 0) continue;

            this.$refs.chart.addData({
              unique_id: parseInt(socket.unique_id),
              data: piece.total === 0 ? 0 : piece.total / piece.amount,
              timestamp: piece.label,
              doFixTime: false,
            });
          }
        }
      } catch (err) {
        console.error(err);
      }
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
