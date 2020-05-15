#include "include/httplib.h"
using namespace httplib;

#include "include/json.hpp"
using json = nlohmann::json;

#include <iostream>
#include <string>
#include <chrono>
#include <thread>

const std::string unique_id("blablakek");

struct Settings {
	int state = 1;
	long long report_delay = 1000;
};

int main() {
	Settings settings;
	Client client("127.0.0.1", 8000);

	bool socket_added = false;

	while (true) {
		if (!socket_added) {
			json payload = {
				{"unique_id", unique_id}
			};

			auto response = client.Post("/api/sockets/add", payload.dump(-1), "application/json");
			socket_added = true;
		}

		{
			json payload = {
				{"power", rand() % 1000},
				{"unique_id", unique_id}
			};

			auto response = client.Post("/api/measurements/add", payload.dump(-1), "application/json");

			if (response && response->status == 200) {
				json response_payload = json::parse(response->body);
				settings.state = response_payload["state"].get<int>();

				std::cout << (settings.state == 0 ? "disabled\n" : "enabled\n");
			}

			std::this_thread::sleep_for(std::chrono::milliseconds(settings.report_delay));
		}
		
	}

	return 0;
}