import StatisticsFormatter from "./StatisticsFormatter";

export default class HourStatisticsFormatter extends StatisticsFormatter {
  constructor() {
    super();
  }

  fillStatistics(statistics) {
    for (let i = 60; i >= 0; i -= 5) {
      const date = new Date();
      // i minutes are converted to milliseconds
      date.setTime(date.getTime() - (i * 60 * 1000));

      const label = this.formatDateString(date);
      
      const hour = date.getUTCHours();
      date.setMinutes(date.getMinutes() - 5);
      const fromMins = date.getMinutes();
      
      date.setMinutes(date.getMinutes() + 5);
      
      const toMins = date.getMinutes();
      
      statistics.push({
        label: label,
        hour: hour,
        from: fromMins,
        to: toMins,
        total: 0,
        amount: 0,
      });
    }
  }

  createTimeRange() {
    const time_to = new Date().toISOString();
    const date = new Date();
    date.setHours(date.getHours() - 1);
    const time_from = date.toISOString();
    return [time_from, time_to];
  }

  formatDateString(date) {
    const hours =
      date.getHours() < 10 ? "0" + date.getHours() : date.getHours();
    const minutes =
      date.getMinutes() < 10
        ? "0" + date.getMinutes()
        : date.getMinutes();
    return `${hours}:${minutes}:00`;
  }
}
