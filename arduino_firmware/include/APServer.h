#pragma once

#include <ESP_EEPROM.h>
#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>
#include <ESP8266HTTPClient.h>

#include "html_pages.h"
#include "Socket.h"

enum SetupStates
{
	DataInROM = 1,
};

class APServer
{
public:
	IPAddress* local_ip = new IPAddress(192, 168, 1, 1);
	IPAddress* gateway = new IPAddress(192, 168, 1, 1);
	IPAddress* subnet = new IPAddress(255, 255, 255, 0);
	const char *ssid = "SPS-AccessPoint";
	const char *password = "12345678";

	byte data_state = EEPROM.read(0);

	void Start();
	void HandleClient();

	bool isDataInROM();

	APServer();
	~APServer();
};


