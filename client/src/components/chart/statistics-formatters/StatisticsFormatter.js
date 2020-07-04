/* eslint-disable no-unused-vars */
export default class StatisticsFormatter {
  constructor() {
    this.yearSubStr = {
      from: 0,
      length: 4,
    };
    this.monthSubStr = {
      from: 5,
      length: 2,
    };
    this.daySubStr = {
      from: 8,
      length: 2,
    };
    this.hourSubStr = {
      from: 11,
      length: 2,
    };
    this.minAndSecSubStr = {
      from: 13,
      length: 6,
    };
  }

  fillStatistics(statistics) {
    throw new Error("You have to implement the method fillStatistics!");
  }

  getDateString() {
    const time = new Date();
    const year = time.getUTCFullYear();
    const month =
      time.getUTCMonth() < 10
        ? "0" + (time.getUTCMonth() + 1)
        : time.getUTCMonth() + 1;
    const day =
      time.getUTCDate() < 10 ? "0" + time.getUTCDate() : time.getUTCDate();
    const hours =
      time.getUTCHours() < 10 ? "0" + time.getUTCHours() : time.getUTCHours();
    const minutes =
      time.getUTCMinutes() < 10
        ? "0" + time.getUTCMinutes()
        : time.getUTCMinutes();
    return `${year}-${month}-${day} ${hours}:${minutes}:00`;
  }

  setToTimeZone(dateString) {
    // Get timezone offset in minutes
    const timeZoneOffset = new Date().getTimezoneOffset();

    const year = dateString.substr(
      this.yearSubStr.from,
      this.yearSubStr.length
    );
    // -1, because months start from 0
    const month =
      parseInt(
        dateString.substr(this.monthSubStr.from, this.monthSubStr.length)
      ) - 1;
    const day = dateString.substr(this.daySubStr.from, this.daySubStr.length);
    const hours = dateString.substr(
      this.hourSubStr.from,
      this.hourSubStr.length
    );

    const newTime = new Date(year, month, day, hours);
    // getting time in milliseconds
    const timeWithOffset = new Date(newTime - timeZoneOffset * 60 * 1000);

    const newYear = timeWithOffset.getFullYear();
    const newMonth =
      timeWithOffset.getMonth() < 10
        ? "0" + (timeWithOffset.getMonth() + 1)
        : timeWithOffset.getMonth() + 1;
    const newDay =
      timeWithOffset.getDate() < 10
        ? "0" + timeWithOffset.getDate()
        : timeWithOffset.getDate();
    const newHours =
      timeWithOffset.getHours() < 10
        ? "0" + timeWithOffset.getHours()
        : timeWithOffset.getHours();

    const minsAndSecs = dateString.substr(
      this.minAndSecSubStr.from,
      this.minAndSecSubStr.length
    );

    return `${newYear}-${newMonth}-${newDay} ${newHours}${minsAndSecs}`;
  }

  createTimeRange() {
    throw new Error("You have to implement the method createTimeRange!");
  }

  formatDateString(date) {
    throw new Error("You have to implement the method formatDateString!");
  }

  getHoursAndMinutes(time) {
    const date = new Date(time);
    const hours =
      date.getHours() < 10 ? "0" + date.getHours() : date.getHours();
    const minutes =
      date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
    const seconds =
      date.getSeconds() < 10 ? "0" + date.getSeconds() : date.getSeconds();

    return `${hours}:${minutes}:${seconds}`;
  }
}
