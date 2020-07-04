#pragma once

#include <ESP_EEPROM.h>
#include <ArduinoJson.h>
#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>
#include <ESP8266HTTPClient.h>

class Socket
{
public:
	const char *unique_id = "123";

	String ssid;
	String pass;
	String backend_ip;

	int switch_state = 0;
	int data_post_rate = 1000;

	void SendToDB(int data);
	void ParseDataFromROM();
	void ConnectToWiFi();
	bool ConnectToDB();
};
