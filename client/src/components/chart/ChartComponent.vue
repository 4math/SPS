<template>
  <div id="chart">
    <b-card id="container">
      <LineChart
        id="line-chart"
        ref="chart"
        :chart-data="dataCollection"
        :options="options"
      />

      <b-dropdown
        id="dropdown-right"
        right
        text="Scale"
        variant="primary"
        class="m-2"
      >
        <b-dropdown-item href="#">
          1 Hour
        </b-dropdown-item>
        <b-dropdown-item href="#">
          1 Day
        </b-dropdown-item>
        <b-dropdown-item href="#">
          1 Week
        </b-dropdown-item>
      </b-dropdown>
    </b-card>
  </div>
</template>

<script>
import LineChart from "./LineChart.js";
import { mapGetters } from "vuex";
import { MAX_DATA_SET_LENGTH } from "@/consts";
import Colors from "@/objects/Colors";

export default {
  name: "ChartComponent",
  components: {
    LineChart,
  },
  data() {
    return {
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
          // mode: "index",
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
      },
    };
  },
  computed: {
    ...mapGetters(["getSockets"]),
  },
  mounted() {
    this.fillData();
    this.$refs.chart.renderChart(this.dataCollection, this.options);
  },
  methods: {
    fillData() {
      const datasets = this.getSockets.map((item, index) => {
        return {
          fill: true,
          label: item.name,
          id: parseInt(item.unique_id),
          // adding opacity to the hex color. 70 is about 44% opacity
          backgroundColor: Colors[index] + "70",
          pointHoverBackgroundColor: Colors[index] + "CC",
          borderColor: Colors[index],
          pointHoverBorderColor: "#0062ff",
          data: [],
        };
      });

      this.dataCollection = {
        labels: [],
        datasets: datasets,
        // [
        // {
        //   fill: true,
        //   label: "Consumed power",
        //   backgroundColor: "#f0f0f0",
        //   // pointBackgroundColor: "#0062ff",
        //   // pointHoverBorderColor: "#0062ff",
        //   // pointHoverBackgroundColor: "#0062ff",
        //   borderColor: "#f87979",
        //   data: [],
        // },
        // ],
      };
    },
    getRandomInt() {
      return Math.floor(Math.random() * (50 - 5 + 1)) + 5;
    },
    getTime() {
      const date = new Date();
      return `${date.getHours()}:${date.getMinutes()}:${date.getSeconds()}`;
    },

    addData(data, id, timestamp) {
      const datasets = this.dataCollection.datasets;
      const labels = this.dataCollection.labels;

      // 2020-06-14T12:38:12.000000Z - slicing off year-month-day and dot precision
      const time = timestamp.slice(11).slice(0, 8);
      labels.push(time);

      datasets
        .find((socket) => socket.id === id)
        .data.push({ x: time, y: data });

      this.checkMaxLength(datasets, labels);

      const chart = this.$refs.chart.$data._chart;
      // this.$refs.chart.renderChart(this.dataCollection, this.options);
      chart.update();
    },

    checkMaxLength(datasets, labels) {
      const lengths = datasets.map((socket) => {
        return { id: socket.id, len: socket.data.length };
      });

      let didRemove = false;

      for (let length of lengths) {
        if (length.len > MAX_DATA_SET_LENGTH) {
          datasets.find((socket) => socket.id === length.id).data.shift();
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
          (socket) => socket.id === parseInt(id, 10)
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
  padding-bottom: 2.5em;
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

#dropdown-right {
  position: absolute;
  bottom: 0;
  right: 0;
}
</style>
