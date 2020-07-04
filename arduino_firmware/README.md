# Arduino firmware for Smart Power Socket project
In order to flash the Arduino, you need to install [Platform.io](https://platformio.org/) extension.

## Warning
If the project does not build and raises `there are no arguments to 'memcpy' that depend on a template parameter`, then include `<string.h>` into the `.pio/libdeps/ESP_EEPROM_ID####/src/ESP_EEPROM.h` file. 

## Project's structure
There are three source files in `src` folder:
 - `main.cpp` for `loop()` and `setup()` routines.
 - `APServer.cpp` for the access point mode, the routine where basic socket setup happens.
 - `Socket.cpp` for the voltage reading and backend connection management.

`include/html_pages.h` contains access point web page sources.