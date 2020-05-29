#pragma once
#include <ESP_EEPROM.h>

const char index_html[] PROGMEM = R"rawliteral(
<!DOCTYPE HTML><html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    html {
     font-family: Arial;
     display: inline-block;
     margin: 0px auto;
     text-align: center;
    }
    body { background: linear-gradient(to right, orange, yellow, green, cyan, blue, violet); }
    h2 { font-size: 3.0rem; }
    p { font-size: 3.0rem; }
    .units { font-size: 1.2rem; }
  </style>
</head>
<body>
  <h2>Smart power socket</h2>

  <form action="/action" method="post">
    <label for="ssid">WiFi ssid:</label>
    <input type="text" id="ssid" name="ssid"><br><br>
    <label for="pass">WiFi password:</label>
    <input type="text" id="pass" name="pass"><br><br>
    <label for="backed">Backend address:</label>
    <input type="backend" id="backend" name="backend"><br><br>
    <input type="submit" value="Submit">
  </form>
</body>
</html>)rawliteral";

const char submit_page[] PROGMEM = R"rawliteral(
<!DOCTYPE HTML><html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    html {
     font-family: Arial;
     display: inline-block;
     margin: 0px auto;
     text-align: center;
    }
    body { background: linear-gradient(to right, orange, yellow, green, cyan, blue, violet); }
    h2 { font-size: 3.0rem; }
    p { font-size: 3.0rem; }
    .units { font-size: 1.2rem; }
  </style>
</head>
<body>
  <h2>Smart power socket</h2>
  <h4>Submit successful</h4>
</body>
</html>)rawliteral";