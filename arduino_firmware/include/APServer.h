#pragma once

#include <ESP_EEPROM.h>
#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>
#include <ESP8266HTTPClient.h>

#include "html_pages.h"
#include "Socket.h"

class APServer
{
public:
	IPAddress* local_ip = new IPAddress(192, 168, 1, 1);
	IPAddress* gateway = new IPAddress(192, 168, 1, 1);
	IPAddress* subnet = new IPAddress(255, 255, 255, 0);
	const char *ssid = "SPS-AccessPoint";
	const char *password = "12345678";

	void Start();
	void HandleClient();

	APServer();
	~APServer();
};


