#include "include/httplib.h"
using namespace httplib;

#include "include/json.hpp"
using json = nlohmann::json;

#include <iostream>
#include <string>
#include <chrono>
#include <thread>

struct Settings {
	int state = 1;
	long long report_delay = 1000;
};

int main() {
	std::string unique_id("blablakek");

	{
		std::cout << "Default unique_id is 'blablakek', enter your unique_id to change or '-' leave it as is: ";
		std::string temp_unique_id;
		std::cin >> temp_unique_id;
		if (temp_unique_id != "-")
			unique_id = temp_unique_id;
	}
	
	Settings settings;
	Client client("127.0.0.1", 8000);

	bool socket_added = false;

	while (true) {
		if (!socket_added) {
			json payload = {
				{"unique_id", unique_id}
			};

			auto response = client.Post("/api/sockets/add", payload.dump(-1), "application/json");
			if (response && response->status != 200) {
				std::cout << response->body << "\n";
			}

			socket_added = true;
		}

		{
			json payload = {
				{"power", rand() % 1000},
				{"unique_id", unique_id}
			};

			auto response = client.Post("/api/measurements/add", payload.dump(-1), "application/json");

			if (response) { 
				if (response->status == 200) {
					json response_payload = json::parse(response->body);
					settings.state = response_payload["state"].get<int>();

					std::cout << (settings.state == 0 ? "disabled\n" : "enabled\n");
				}
				else {
					std::cout << response->body << "\n";
				}
			}

			std::this_thread::sleep_for(std::chrono::milliseconds(settings.report_delay));
		}
		
	}

	return 0;
}