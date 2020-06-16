import io from "socket.io-client";

const socket: SocketIOClient.Socket = io.connect("ws://localhost:8090", {
  transports: ["websocket"],
  upgrade: false,
});

socket.on("connect", function () {
  console.log("CONNECT");

  socket.on("event", function (data: string) {
    console.log("EVENT", data);
  });

  socket.on("messages.new", function (data: string) {
    console.log("NEW PRIVATE MESSAGE", data);
  });

  socket.on("disconnect", function () {
    console.log("disconnect");
  });

  // Kick it off
  // Can be any channel. For private channels, Laravel should pass it upon page load (or given by another user).
  const channel: string = "test";

  socket.emit("subscribe-to-channel", { channel: channel });
  console.log(`SUBSCRIBED TO <${channel}>`);
});

