#include "APServer.h"

ESP8266WebServer server(80);

void APServer::Start()
{
	Serial.println("Starting AP");
	WiFi.softAP(ssid, password, 7);
	WiFi.softAPConfig(*local_ip, *gateway, *subnet);
	delay(100);
	server.begin();
}

void APServer::HandleClient()
{
	server.handleClient();
}

APServer::APServer()
{
	server.on("/", []() {
		server.send(200, "text/html", index_html);
	});

	server.on("/action", [&]() { // parsing data recieved from form
		String wifi_ssid = server.arg(0);
		String wifi_pass = server.arg(1);
		String backend_ip = server.arg(2);

		int EEPROM_pos = 1;

		for (int i = 0; i < wifi_ssid.length(); i++)
			EEPROM.write(EEPROM_pos++, wifi_ssid[i]);
		EEPROM.write(EEPROM_pos++, '\0');

		for (int i = 0; i < wifi_pass.length(); i++)
			EEPROM.write(EEPROM_pos++, wifi_pass[i]);
		EEPROM.write(EEPROM_pos++, '\0');

		for (int i = 0; i < backend_ip.length(); i++)
			EEPROM.write(EEPROM_pos++, backend_ip[i]);
		EEPROM.write(EEPROM_pos++, '\0');

		EEPROM.write(0, DataInROM); // setup state written in 0th byte

		EEPROM.commit();

		server.send(200, "text/html", submit_page);
		delay(1000);

		server.stop();
		WiFi.softAPdisconnect(true);

		Serial.println("AP disabled");
	});
}

APServer::~APServer()
{
}