import StatisticsFormatter from "./StatisticsFormatter";

export default class DayStatisticsFormatter extends StatisticsFormatter {
  constructor() {
    super();
  }

  fillStatistics(statistics) {
    for (let i = 24; i >= 0; --i) {
      const date = new Date();
      date.setHours(date.getHours() - i);

      const label = this.formatDateString(date);

      const hour = parseInt(
        label.substr(this.hourSubStr.from, this.hourSubStr.length)
      );
      const day = parseInt(
        label.substr(this.daySubStr.from, this.daySubStr.length)
      );

      // timezone fitting is needed
      statistics.push({
        label: this.setToTimeZone(label),
        hour: hour,
        day: day,
        total: 0,
        amount: 0,
      });
    }
  }

  createTimeRange() {
    const time_to = this.getDateString();

    let oneDayAgo = new Date();
    oneDayAgo.setDate(oneDayAgo.getDate() - 1);
    const time_from = this.formatDateString(oneDayAgo);

    return [time_from, time_to];
  }

  formatDateString(date) {
    const year = date.getUTCFullYear();
    const month =
      date.getUTCMonth() < 10
        ? "0" + (date.getUTCMonth() + 1)
        : date.getUTCMonth() + 1;
    const day =
      date.getUTCDate() < 10 ? "0" + date.getUTCDate() : date.getUTCDate();
    const hours =
      date.getUTCHours() < 10 ? "0" + date.getUTCHours() : date.getUTCHours();
    return `${year}-${month}-${day} ${hours}:00:00`;
  }
}
