# Smart Power Socket
description

# How to run this project
  1) Follow the instructions in README file to install all dependencies in the [arduino_firmware](https://github.com/4math/SPS/tree/develop/arduino_firmware), [backend](https://github.com/4math/SPS/tree/develop/backend), [client](https://github.com/4math/SPS/tree/develop/client), [ws-server](https://github.com/4math/SPS/tree/develop/ws-server) folders.
     - If you do not have Arduino, you can use emulator. Follow the instructions in README in [emulator](https://github.com/4math/SPS/tree/develop/emulator).
     - Project also requires [Redis](https://redis.io/) server.
  2) To run the project: 
        - Go to the [backend](https://github.com/4math/SPS/tree/develop/backend) folder and use the command `php artisan serve`
        - Go to the [ws-server](https://github.com/4math/SPS/tree/develop/ws-server) folder and use the command `npm run start:prod` for production run or `npm run start:dev` for development run.
        - Go to the [client](https://github.com/4math/SPS/tree/develop/client) folder and use the command `npm run serve` for development build or `npm run build` and use your http server. 
        -  Run Redis server using redis-server utility.  



# Goal of the project

# What else has to be done