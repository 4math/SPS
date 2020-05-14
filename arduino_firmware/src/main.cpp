#include "Socket.h"
#include "APServer.h"

Socket socket;
APServer ap_server;

void setup()
{
    Serial.begin(115200); // for debugging purposes
    EEPROM.begin(255);
    pinMode(LED_BUILTIN, OUTPUT);

    ap_server.Start();

    byte setup_state = 0;
    
    do
    {
        // wait until user provides data and while they are written to memory.
        ap_server.HandleClient();
        setup_state = EEPROM.read(0);
    } while (setup_state != DataInROM);

    socket.ParseDataFromROM();
    socket.ConnectToWiFi();
    socket.ConnectToDB();
}

void loop()
{
    if (WiFi.status() == WL_CONNECTED)
    {
        socket.SendToDB(random(1000));

        digitalWrite(LED_BUILTIN, socket.switch_state);
    }
}