# Arduino firmware for Smart Power Socket project
In order to flash the Arduino, you need to install [Platform.io](https://platformio.org/) extension.

## Project's structure
There are three source files in `src` folder:
 - `main.cpp` for `loop()` and `setup()` routines.
 - `APServer.cpp` for the access point mode, the routine where basic socket setup happens.
 - `Socket.cpp` for the voltage reading and backend connection management.

`include/html_pages.h` contains access point web page sources.