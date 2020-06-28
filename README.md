# Smart Power Socket
 - It is a small project of the future students of the university during which we tried to create our own smart device and the environment for its work. 
 
# Goal of the project
  1) Create a Smart Power Socket device which will be able to measure power input and collect statistics about power usage.
  2) Create software ecosystem where user ir able to turn on/off device, to browse the statistics of power usage and to create conditions which the smart device should execute.
  3) Gain new knowledge in working with microcontrollers and creating full-stack web application.  

# How to run this project
  1) Follow the instructions in README file to install all dependencies in the [arduino_firmware](https://github.com/4math/SPS/tree/develop/arduino_firmware), [backend](https://github.com/4math/SPS/tree/develop/backend), [client](https://github.com/4math/SPS/tree/develop/client), [ws-server](https://github.com/4math/SPS/tree/develop/ws-server) folders.
     - If you do not have Arduino, you can use emulator. Follow the instructions in README in [emulator](https://github.com/4math/SPS/tree/develop/emulator).
     - Project also requires [Redis](https://redis.io/) server.
  2) To run the project: 
        - Go to the [backend](https://github.com/4math/SPS/tree/develop/backend) folder and use the command `php artisan serve`
        - Go to the [ws-server](https://github.com/4math/SPS/tree/develop/ws-server) folder and use the command `npm run start:prod` for production run or `npm run start:dev` for development run.
        - Go to the [client](https://github.com/4math/SPS/tree/develop/client) folder and use the command `npm run serve` for development build or `npm run build` and use your http server. 
        -  Run Redis server using redis-server utility.  



# What else has to be done
Some of the features which were not implemented yet:
- [ ] Zooming and panning for the graphs. Something similiar to [Highcharts](https://www.highcharts.com/demo/dynamic-master-detail). The current implementation with Chart.js does not allow to create easy to use zooming and panning.
- [ ] Events  page. The idea is to allow user to choose conditions how the Smart Power Socket should work. For example, if the power is more than 500W, then the socket should stop working.
- [ ] Better UI/UX. It is not possible to use Tabs for navigation as well as better design, as UI was created by technicians, not designers.
- [ ] Tests are not present. Therefore, it is quite important to write some tests for all parts of the project in the future. 
- [ ] Due to lockdown in the spring 2020, it was not possible to create real Smart Power Socket. Therefore, there is a lot of work to create our own smart device. 