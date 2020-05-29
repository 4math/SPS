import express from "express";
import http from "http";
import redis from "redis";
import * as dotenv from "dotenv";
import io from "socket.io";
import Log from "./../utils/logger";

dotenv.config();

const app: express.Application = express();

const server: http.Server = http.createServer(app);

const socket: io.Server = io.listen(server, {
  transports: ["websocket"],
  serveClient: false,
});

const logger: Log = new Log();

/*
 * Redis pub/sub
 */

// Listen to local Redis broadcasts
const sub = redis.createClient({
  host: process.env.REDIS_HOST,
  port: parseInt(process.env.REDIS_PORT, 10),
});

sub.on("error", function (error) {
  console.log("ERROR " + error);
});

sub.on("subscribe", function (channel, count) {
  logger.info(`SUBSCRIBE, ${channel}, ${count}`);
  console.log("SUBSCRIBE", channel, count);
});

interface IPayload {
  event: string;
  data: string;
}

// Handle messages from channels we're subscribed to
sub.on("message", (channel, message) => {
  console.log("INCOMING MESSAGE", channel, message);

  try {
    const payload: IPayload = JSON.parse(message);
    // Send the data through to any client in the channel room (!)
    // (i.e. server room, usually being just the one user)
    socket.sockets.in(channel).emit(payload.event, payload.data);
  } catch (err) {
    console.error("Couldn't parse: " + err);
  }
});

socket.sockets.on("connection", function (socket) {
  console.log("NEW CLIENT CONNECTED");

  socket.send("welcome!");

  socket.on("subscribe-to-channel", function (data) {
    console.log("SUBSCRIBE TO CHANNEL", data);

    // Subscribe to the Redis channel using our global subscriber
    sub.subscribe(data.channel);

    // Join the (somewhat local) server room for this channel. This
    // way we can later pass our channel events right through to
    // the room instead of broadcasting them to every client.
    socket.join(data.channel);
  });

  socket.on("disconnect", function () {
    console.log("DISCONNECT");
  });
});

app.get("/", (req, res) => {
  res.send("The sedulous hyena ate the antelope!");
});

const port: string = process.env.PORT;

server.listen(port, () => {
  return console.log(`server is listening on ${port}`);
});
