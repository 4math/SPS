<template>
  <div id="chart">
    <b-card id="container">
      <LineChart
        v-if="graph === LINE"
        id="line-chart"
        ref="chart"
        class="chart"
        :chart-data="dataCollection"
        :options="options"
      />

      <BarChart
        v-if="graph === BAR"
        id="line-chart"
        ref="chart"
        :chart-data="dataCollection"
        :options="options"
      />

      <b-form-select
        id="scale-select"
        v-model="internalSelected"
        :options="selectedOptions"
        size="md"
      />
    </b-card>
  </div>
</template>

<script>
import LineChart from "./LineChart.js";
import BarChart from "./BarChart.js";
// eslint-disable-next-line no-unused-vars
// import zoom from "chartjs-plugin-zoom";
import { mapGetters } from "vuex";
import { MAX_DATA_SET_LENGTH, SCALE_OPTIONS, GRAPHS } from "@/consts";
import Colors from "@/objects/Colors";

export default {
  name: "ChartComponent",
  components: {
    LineChart,
    BarChart,
  },
  props: {
    selected: {
      type: String,
      required: true,
    },
    graph: {
      type: String,
      required: true,
    },
    isScrolled: {
      type: Boolean,
      required: true,
    },
  },
  data() {
    return {
      LINE: GRAPHS.LINE,
      BAR: GRAPHS.BAR,
      selectedOptions: [
        { value: SCALE_OPTIONS.REALTIME, text: "Scale: Real-time" },
        { value: SCALE_OPTIONS.ONEHOUR, text: "Scale: 1 Hour" },
        { value: SCALE_OPTIONS.ONEDAY, text: "Scale: 1 Day" },
        { value: SCALE_OPTIONS.ONEWEEK, text: "Scale: 1 Week" },
      ],
      dataCollection: {},
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          xAxes: [{}],
        },
        animation: {
          duration: 200,
        },
        tooltips: {
          // backgroundColor: "#4F5565", // greyish
          backgroundColor: "#2A2B2E",
          titleFontStyle: "normal",
          titleFontSize: 18,
          bodyFontFamily: "'Proxima Nova', sans-serif",
          cornerRadius: 3,
          // bodyFontColor: '#20C4C8', //neon
          bodyFontColor: "#87FF65",
          bodyFontSize: 14,
          xPadding: 14,
          yPadding: 14,
          displayColors: false,
          mode: "nearest",
          intersect: false,
          callbacks: {
            title: (tooltipItem, data) => {
              let dataset = data.datasets[tooltipItem[0].datasetIndex];
              let currentValue = dataset.data[tooltipItem[0].index];
              return `${
                this.getSockets[tooltipItem[0].datasetIndex].name
              }\n\n🕒 ${currentValue.x.toLocaleString()}`;
            },
            label: (tooltipItem, data) => {
              let dataset = data.datasets[tooltipItem.datasetIndex];
              let currentValue = dataset.data[tooltipItem.index];
              return `⚡ Consumed power: ${currentValue.y.toLocaleString()}`;
            },
          },
        },
        legend: {
          labels: {
            fontFamily: "sans-serif",
            fontSize: 18,
          },
        },
        // plugins: {
        //   zoom: {
        //     pan: {
        //       enabled: true,
        //       mode: "x",
        //       speed: 100,
        //       // rangeMax: {
        //       //   x: 5
        //       // }
        //     },
        //     zoom: {
        //       enabled: true,
        //       speed: 10,
        //       mode: "x",
        //       sensitivity: 0.5,
        //       // rangeMax: {
        //       //   x: 2
        //       // },
        //     },
        //   },
        // },
      },
    };
  },
  computed: {
    ...mapGetters(["getSockets"]),
    internalSelected: {
      get() {
        return this.selected;
      },

      set(option) {
        this.$emit("onSelectedChange", option);
      },
    },
  },
  watch: {
    graph: function() {
      this.clearChart();
    },
  },
  mounted() {
    this.clearChart();
  },
  methods: {
    fillData() {
      const datasets = this.getSockets.map((item, index) => ({
        fill: true,
        label: item.name,
        id: parseInt(item.id),
        unique_id: parseInt(item.unique_id),
        // adding opacity to the hex color. 70 is about 44% opacity
        backgroundColor: Colors[index] + "70",
        pointHoverBackgroundColor: Colors[index] + "CC",
        borderColor: Colors[index],
        pointHoverBorderColor: "#0062ff",
        data: [],
      }));

      this.dataCollection = {
        labels: [],
        datasets: datasets,
      };
    },

    clearChart() {
      this.fillData();
      this.$refs.chart.renderChart(this.dataCollection, this.options);
    },

    editTime(timestamp, fixTimeZone = true) {
      // 2020-06-14T12:38:12.000000Z - slicing off year-month-day and dot precision
      timestamp = timestamp.slice(11).slice(0, 8);
      let hour = parseInt(timestamp.substr(0, 2));
      if (fixTimeZone) {
        const offset = new Date().getTimezoneOffset() / -60;
        hour += offset;
      }
      timestamp = hour + timestamp.substr(2);
      return timestamp;
    },

    pushLabel(timestamp, doFixTime = true, fixTimeZone = true) {
      const labels = this.dataCollection.labels;
      if (doFixTime) {
        timestamp = this.editTime(timestamp, fixTimeZone);
      }
      labels.push(timestamp);
    },

    addData({ unique_id, data, timestamp, doFixTime = true }) {
      const datasets = this.dataCollection.datasets;
      const labels = this.dataCollection.labels;

      if (doFixTime) {
        timestamp = this.editTime(timestamp);
      }

      datasets
        .find((socket) => socket.unique_id === unique_id)
        .data.push({ x: timestamp, y: data });

      if (this.internalSelected === SCALE_OPTIONS.REALTIME) {
        this.checkMaxLength(datasets, labels);
      }

      const chart = this.$refs.chart.$data._chart;
      // this.$refs.chart.renderChart(this.dataCollection, this.options);
      chart.update();
    },

    checkMaxLength(datasets, labels) {
      const lengths = datasets.map((socket) => ({
        id: socket.unique_id,
        len: socket.data.length,
      }));

      let didRemove = false;

      for (let length of lengths) {
        if (length.len > MAX_DATA_SET_LENGTH) {
          datasets
            .find((socket) => socket.unique_id === length.id)
            .data.shift();
          didRemove = true;
        }
      }

      if (didRemove) {
        labels.shift();
      }
    },
    turnOffSpecificGraphs() {
      const chart = this.$refs.chart.$data._chart;
      const id = this.$route.params.id;
      if (id) {
        const datasets = chart.data.datasets;
        const element = datasets.find(
          (socket) => socket.unique_id === parseInt(id, 10)
        );
        const index = datasets.indexOf(element);
        for (let i = 0; i < datasets.length; i++) {
          if (i !== index) {
            chart.getDatasetMeta(i).hidden = true;
          }
        }
        chart.update();
      }
    },
  },
};
</script>

<style scoped>
#container {
  margin: 0 auto;
  padding: 0 20px;
  width: 100%;
  height: 100% !important;
  box-sizing: border-box;
  padding: 5px;
  position: relative;
}

#line-chart {
  height: 100%;
  width: 100%;
  display: block;
}

.card-body {
  padding-bottom: 3.5rem;
  padding-right: 0.5rem;
  padding-left: 0.5rem;
}

#chart {
  margin: 0 auto;
  padding: 0 20px;
  width: 100%;
  height: 100% !important;
  display: block;
  box-sizing: border-box;
}

.btn {
  margin: 5px;
}

#scale-select {
  position: absolute;
  bottom: 0;
  right: 0;
  width: 20%;
  margin: 5px;
}

#scale-select:hover {
  cursor: pointer;
}

.chartAreaWrapper {
  width: 80%;
  overflow-x: scroll;
}
</style>
