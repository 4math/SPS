import * as fs from "fs";
import { Coloring } from "./colors";

export default class Logger {
  private logger: fs.WriteStream;

  constructor() {
    this.logger = fs.createWriteStream("logs/server.log", {
      flags: "a",
    });
  }

  public info(data: string): void {
    const time: string = this.getTime();
    const prepend: string = "[INFO]";
    const message: string = ` (${time}) - ${data}`;
    this.logger.write(prepend + message + "\n");
    console.log(Coloring.colorize(prepend, Coloring.Colors.fg.Green) + message);
  }

  public warn(data: string): void {
    const time: string = this.getTime();
    const prepend: string = "[WARNING]";
    const message: string = ` (${time}) - ${data}`;
    this.logger.write(prepend + message + "\n");
    console.log(
      Coloring.colorize(prepend, Coloring.Colors.fg.Yellow) + message
    );
  }

  public error(data: string): void {
    const time: string = this.getTime();
    const prepend: string = "[ERROR]";
    const message: string = ` (${time}) - ${data}`;
    this.logger.write(prepend + message + "\n");
    console.log(Coloring.colorize(prepend, Coloring.Colors.fg.Red) + message);
  }

  private getTime(): string {
    let time: string = new Date().toUTCString();
    time = time.split(" ").slice(0, 5).join(" ");
    return time;
  }
}
