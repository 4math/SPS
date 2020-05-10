#include <ESP_EEPROM.h>
#include <ArduinoJson.h>
#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>
#include <ESP8266HTTPClient.h>

IPAddress local_ip(192, 168, 1, 1);
IPAddress gateway(192, 168, 1, 1);
IPAddress subnet(255, 255, 255, 0);

ESP8266WebServer server(80);

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

const char *unique_id = "blablablakek";

const char *ap_ssid = "ESP8266-AccessPoint";
const char *ap_password = "12345678";

byte setup_state = 0;
String ssid, pass, backend;

const int LED_pin = 1;

void setup()
{
	Serial.begin(115200); // for debugging purposes
	EEPROM.begin(255);
	pinMode(LED_BUILTIN, OUTPUT);

	setup_state = EEPROM.read(0);

	if (setup_state != 1)
	{
		Serial.println("Starting AP");
		WiFi.softAP(ap_ssid, ap_password, 7);
		WiFi.softAPConfig(local_ip, gateway, subnet);
		delay(100);

		server.on("/", []() {
			server.send(200, "text/html", index_html);
		});

		server.on("/action", [&]() { // parsing data recieved from form
			ssid = server.arg(0);
			pass = server.arg(1);
			backend = server.arg(2);

			int EEPROM_pos = 1;
			EEPROM.write(0, 1); // setup state written in 0th byte

			for (int i = 0; i < ssid.length(); i++)
				EEPROM.write(EEPROM_pos++, ssid[i]);
			EEPROM.write(EEPROM_pos++, '\0');

			for (int i = 0; i < pass.length(); i++)
				EEPROM.write(EEPROM_pos++, pass[i]);
			EEPROM.write(EEPROM_pos++, '\0');

			for (int i = 0; i < backend.length(); i++)
				EEPROM.write(EEPROM_pos++, backend[i]);
			EEPROM.write(EEPROM_pos++, '\0');

			EEPROM.commit();

			server.send(200, "text/html", submit_page);
			delay(1000);

			server.stop();
			WiFi.softAPdisconnect(true);

			Serial.println("AP disabled, connecting now to WiFi");
			WiFi.mode(WIFI_STA);
			WiFi.begin(ssid, pass);
			while (WiFi.status() != WL_CONNECTED)
			{
				switch (WiFi.status())
				{
				case WL_IDLE_STATUS:
				{
					Serial.println("wifi status: WL_IDLE_STATUS");
					break;
				}
				case WL_NO_SSID_AVAIL:
				{
					Serial.println("wifi status: WL_NO_SSID_AVAIL");
					break;
				}
				case WL_CONNECT_FAILED:
				{
					Serial.println("wifi status: WL_CONNECT_FAILED (password is incorrect");
					break;
				}
				case WL_DISCONNECTED:
				{
					Serial.println("wifi status: WL_DISCONNECTED (module is not configured in station mode)");
					break;
				}
				default:
					Serial.println("wifi status: unknown");
				}

				delay(500);
			}
			Serial.println("Connected!");
		});

		server.begin();
	}
	else if (setup_state == 1)
	{
		Serial.println("Reading ssid, pass, data.");

		int EEPROM_pos = 1;
		char chr = EEPROM.read(EEPROM_pos++);
		while (chr != '\0')
		{
			ssid += chr;
			chr = EEPROM.read(EEPROM_pos++);
		}

		chr = EEPROM.read(EEPROM_pos++);
		while (chr != '\0')
		{
			pass += chr;
			chr = EEPROM.read(EEPROM_pos++);
		}

		chr = EEPROM.read(EEPROM_pos++);
		while (chr != '\0')
		{
			backend += chr;
			chr = EEPROM.read(EEPROM_pos++);
		}

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
}

void AddSocket()
{
	WiFiClient client;
	HTTPClient http;

	http.begin(client, "http://" + backend + ":8000/api/sockets/add");
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
	}
}

struct SocketStatus
{
	int state = 0;
	int data_post_rate = 1000;
	int status_update_rate = 1000;
} ss;

void PostDataGetState(int data_)
{
	WiFiClient client;
	HTTPClient http;

	http.begin(client, "http://" + backend + ":8000/api/measurements/add");
	http.addHeader("Content-Type", "application/json");

	String payload;
	DynamicJsonDocument payload_doc(128);
	payload_doc["power"] = data_;
	payload_doc["unique_id"] = unique_id;
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
		ss.state = response_payload_doc["state"];
		Serial.println(response_payload);
	}
}

bool socket_added = false;

void loop()
{
	if (setup_state == 0)
		server.handleClient();
	else
	{
		if (WiFi.status() == WL_CONNECTED)
		{
			if (!socket_added)
			{
				AddSocket();
				socket_added = true;
			}

			PostDataGetState(random(1000));

			digitalWrite(LED_BUILTIN, ss.state);
		}
	}
}