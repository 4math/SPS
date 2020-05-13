<template>
  <div id="charts">
    <h1>Welcome to the Charts tab!</h1>
    <b-card id="container">
      <LineChart :chart-data="dataCollection" :options="options" />
      <b-button @click="fillData()">
        Randomize
      </b-button>
    </b-card>
  </div>
</template>

<script>
import LineChart from "./LineChart.js";

export default {
  name: "ChartsPage",
  components: {
    LineChart,
  },
  data() {
    return {
      dataCollection: {},
      options: {
        responsive: true,
        maintainAspectRatio: false,
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
        labels: new Array(6)
          .fill(null)
          // eslint-disable-next-line no-unused-vars
          .map((_) => this.getRandomInt())
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
            data: new Array(6).fill(null).map((_) => this.getRandomInt()),
          },
        ],
      };
    },
    getRandomInt() {
      return Math.floor(Math.random() * (50 - 5 + 1)) + 5;
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
}

#charts {
  max-width: 90rem;
  margin: 0 auto;
  padding: 0 20px;
  width: 100%;
  box-sizing: border-box;
}
</style>
