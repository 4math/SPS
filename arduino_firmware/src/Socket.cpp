#include "Socket.h"

void Socket::SendToDB(int data)
{
	WiFiClient client;
	HTTPClient http;

	http.begin(client, "http://" + backend_ip + ":8000/api/measurements/add");
	http.addHeader("Content-Type", "application/json");

	DynamicJsonDocument payload_doc(128);
	payload_doc["power"] = data;
	payload_doc["unique_id"] = unique_id;
	String payload;
	serializeJson(payload_doc, payload);

	int http_code = http.POST(payload);

	if (http_code != 200)
	{
		delay(1000);
		Serial.printf("[HTTP] POST... failed, error: %s\n", http.errorToString(http_code).c_str());
	}
	else
	{
		const String &response_payload = http.getString();
		DynamicJsonDocument response_payload_doc(128);
		deserializeJson(response_payload_doc, response_payload);
		switch_state = response_payload_doc["state"];
		Serial.println(response_payload);
	}
}

void Socket::ParseDataFromROM()
{
	Serial.println("Reading ssid, pass, data.");

	int EEPROM_pos = 1;

	ssid.clear();
	char chr = EEPROM.read(EEPROM_pos++);
	while (chr != '\0')
	{
		ssid += chr;
		chr = EEPROM.read(EEPROM_pos++);
	}

	pass.clear();
	chr = EEPROM.read(EEPROM_pos++);
	while (chr != '\0')
	{
		pass += chr;
		chr = EEPROM.read(EEPROM_pos++);
	}

	backend_ip.clear();
	chr = EEPROM.read(EEPROM_pos++);
	while (chr != '\0')
	{
		backend_ip += chr;
		chr = EEPROM.read(EEPROM_pos++);
	}
}

void Socket::ConnectToWiFi()
{
	Serial.println("Connecting to WiFi");
	WiFi.mode(WIFI_STA);
	WiFi.begin(ssid, pass);
	while (WiFi.status() != WL_CONNECTED)
	{
		delay(500);
		Serial.print(".");
	}
	Serial.println("Connected!");
}

bool Socket::ConnectToDB()
{
	WiFiClient client;
	HTTPClient http;

	http.begin(client, "http://" + backend_ip + ":8000/api/sockets/add");
	http.addHeader("Content-Type", "application/json");

	String payload;
	DynamicJsonDocument payload_doc(128);
	payload_doc["unique_id"] = unique_id;
	serializeJson(payload_doc, payload);

	int http_code = http.POST(payload);

	if (http_code != 200)
	{
		delay(1000);
		Serial.printf("[HTTP] POST... failed, error: %s\n", http.errorToString(http_code).c_str());
		return false;
	}
	return true;
}

Socket::Socket(/* args */)
{
}

Socket::~Socket()
{
}