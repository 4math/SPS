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
          duration: 0,
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
        legend: {},
      },
    };
  },
  computed: {
    ...mapGetters(["getSockets"]),
  },
  mounted() {
    this.fillData();
  },
  methods: {
    fillData() {
      const datasets = this.getSockets.map((item) => {
        return {
          fill: true,
          label: item.name,
          id: parseInt(item.unique_id),
          backgroundColor: item.id % 2 == 0 ?  [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ] : [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
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
    addData(data, id) {
      const time = this.getTime();
      this.dataCollection.labels.push(time);
      console.log(this.dataCollection.datasets.find(socket => socket.id === id));
      this.dataCollection.datasets.find(socket => socket.id === id).data.push({x: time, y: data});
      // this.dataCollection.datasets[0].data.push(data);
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
  height: 100%;
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
  height: calc(100% - 82px);
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
