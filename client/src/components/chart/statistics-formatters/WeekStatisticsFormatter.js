import StatisticsFormatter from "./StatisticsFormatter";

export default class WeekStatisticsFormatter extends StatisticsFormatter {
  constructor() {
    super();
  }

  fillStatistics(statistics) {
    for (let i = 7; i >= 0; --i) {
      const date = new Date();
      date.setDate(date.getDate() - i);

      const label = this.formatDateString(date);

      // `2020-06-05 17:00:00` take 05 from string and parse it into 5
      const day = parseInt(
        label.substr(this.daySubStr.from, this.daySubStr.length)
      );

      statistics.push({
        label: label,
        day: day,
        total: 0,
        amount: 0,
      });
    }
  }

  createTimeRange() {
    const date = new Date();
    date.setDate(date.getDate() + 1);

    const time_to = this.formatDateString(date);
    const time_from = this._oneWeekAgo();

    return [time_from, time_to];
  }

  formatDateString(date) {
    const year = date.getUTCFullYear();
    const month =
      date.getUTCMonth() < 10 ? "0" + (date.getUTCMonth() + 1) : date.getUTCMonth() + 1;
    const day = date.getUTCDate() < 10 ? "0" + date.getUTCDate() : date.getUTCDate();
    return `${year}-${month}-${day}`;
  }

  _oneWeekAgo() {
    const oneWeekAgo = new Date();
    oneWeekAgo.setDate(oneWeekAgo.getDate() - 7);
    const year = oneWeekAgo.getUTCFullYear();
    const month =
      oneWeekAgo.getUTCMonth() < 10
        ? "0" + (oneWeekAgo.getUTCMonth() + 1)
        : oneWeekAgo.getUTCMonth() + 1;
    const day =
      oneWeekAgo.getUTCDate() < 10
        ? "0" + oneWeekAgo.getUTCDate()
        : oneWeekAgo.getUTCDate();
    return `${year}-${month}-${day}`;
  }
}
