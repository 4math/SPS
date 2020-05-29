<template>
  <div id="charts">
    <h1>Welcome to the Charts tab!</h1>
    <ChartComponent />
  </div>
</template>

<script>
import ChartComponent from "@/components/chart/ChartComponent";

export default {
  name: "ChartsPage",
  components: {
    ChartComponent,
  },
  data() {
    return {};
  },
  computed: {},
  mounted() {},
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
