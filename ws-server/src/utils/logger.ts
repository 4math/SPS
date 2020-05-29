import * as fs from "fs";

export default class Log {
  private logger: fs.WriteStream;

  constructor() {
    this.logger = fs.createWriteStream("logs/server.log", {
      flags: "a",
    });
  }

  /**
   * info
   */
  public info(data: string): void {
    const time: string = this.getTime();
    const message: string = `[INFO] (${time}) - ${data}`;
    this.logger.write(message + "\n");
    console.log(message);
  }

  private getTime(): string {
    //TODO set host's time
    let time: string = new Date().toUTCString();
    time = time.split(' ').slice(0, 5).join(' ');
    return time;
  }
}
