<template>
  <div id="chart">
    <b-card id="container">
      <LineChart ref="chart" :chart-data="dataCollection" :options="options" />

      <b-button id="add-data" @click="addData()">
        Add Data
      </b-button>
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
          bodyFontColor: '#87FF65',
          bodyFontSize: 14,
          xPadding: 14,
          yPadding: 14,
          displayColors: false,
          mode: "index",
          intersect: false,
          callbacks: {
            title: (tooltipItem) => {
              return `🕒 ${tooltipItem[0].xLabel}`;
            },
            label: (tooltipItem, data) => {
              let dataset = data.datasets[tooltipItem.datasetIndex];
              let currentValue = dataset.data[tooltipItem.index];
              return `⚡ Consumed power: ${currentValue.toLocaleString()}`;
            },
          },
          
        },
      },
    };
  },
  computed: {},
  mounted() {
    this.fillData();
  },
  methods: {
    fillData() {
      this.dataCollection = {
        labels: new Array(3)
          .fill(null)
          // eslint-disable-next-line no-unused-vars
          .map((_) => this.getTime())
          .sort((a, b) => {
            if (a > b) {
              return 1;
            }
            if (b > a) {
              return -1;
            }
            return 0;
          }),
        datasets: [
          {
            label: "Consumed power",
            backgroundColor: "#f87979",
            // eslint-disable-next-line no-unused-vars
            data: new Array(3).fill(null).map((_) => this.getRandomInt()),
          },
        ],
      };
    },
    getRandomInt() {
      return Math.floor(Math.random() * (50 - 5 + 1)) + 5;
    },
    getTime() {
      const date = new Date();
      return `${date.getHours()}:${date.getMinutes()}:${date.getSeconds()}`;
    },
    addData() {
      this.dataCollection.labels.push(this.getTime());
      this.dataCollection.datasets[0].data.push(this.getRandomInt());
      this.$refs.chart.renderChart(this.dataCollection, this.options);
    },
  },
};
</script>

<style scoped>
#container {
  margin: 0 auto;
  padding: 0 20px;
  width: 100%;
  box-sizing: border-box;
  padding: 5px;
  position: relative;
}

#chart {
  max-width: 90rem;
  margin: 0 auto;
  padding: 0 20px;
  width: 100%;
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
