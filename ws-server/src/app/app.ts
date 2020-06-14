import express from "express";
import http from "http";
import redis from "redis";
import * as dotenv from "dotenv";
import io from "socket.io";
import Logger from "./../utils/logger";

dotenv.config();

const app: express.Application = express();

const server: http.Server = http.createServer(app);

const socket: io.Server = io.listen(server, {
  transports: ["websocket"],
  serveClient: false,
});

const logger: Logger = new Logger();

/*
 * Redis pub/sub
 */

// Listen to local Redis broadcasts
const sub = redis.createClient({
  host: process.env.REDIS_HOST,
  port: parseInt(process.env.REDIS_PORT, 10),
});

sub.on("error", function (error) {
  logger.error(error);
});

sub.on("subscribe", function (channel, count) {
  logger.info(`SUBSCRIBE, ${channel}, ${count}`);
});

interface IUsers {
  [key: number]: io.Socket[];
}

const users: IUsers = {};

socket.sockets.on("connection", (socket) => {
  logger.info("NEW CLIENT CONNECTED");

  const userId: number = socket.handshake.query.userid as number;
  console.log(users);
  if (!users[userId]) {
    users[userId] = [];
  } 
  users[userId].push(socket);
  

  socket.on("subscribe-to-channel", function (data) {
    logger.info("SUBSCRIBE TO CHANNEL " + data);

    // Subscribe to the Redis channel using our global subscriber
    sub.subscribe(data.channel);

    // Join the (somewhat local) server room for this channel. This
    // way we can later pass our channel events right through to
    // the room instead of broadcasting them to every client.
    socket.join(data.channel);
  });

  socket.on("disconnect", () => {
    logger.info("DISCONNECT");
    const index: number = users[userId].indexOf(socket);
    users[userId].splice(index, 1);
    console.log(users);
  });
});

interface IPayload {
  event: string;
  data: string;
  userId: number;
  socketId: number;
}

// Handle messages from channels we're subscribed to
sub.on("message", (channel, message) => {
  logger.info(`INCOMING MESSAGE, ${channel}, ${message}`);

  try {
    const payload: IPayload = JSON.parse(message);
    // Send the data through to any client in the channel room (!)
    // (i.e. server room, usually being just the one user)
    // socket.sockets.in(channel).emit(payload.event, payload.data, payload.userId);
    users[payload.userId].forEach((socket) => {
      socket.emit(payload.event, [payload.data, payload.socketId]);
    });
    // users[payload.userId].emit(payload.event, [payload.data, payload.socketId]);
  } catch (err) {
    logger.error("Couldn't parse: " + err);
  }
});

const port: string = process.env.PORT;

server.listen(port, () => {
  logger.info(`server is listening on ${port}`);
});
