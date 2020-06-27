#include "include/httplib.h"
using namespace httplib;

#include "include/json.hpp"
using json = nlohmann::json;

#include <iostream>
#include <sstream>
#include <string>
#include <chrono>
#include <thread>
#include <fstream>
#include <limits>

struct Settings {
	int state = 1;
	long long report_delay = 1000;
};

int main() {
	unsigned int unique_id = 123456;
	std::string address("localhost");
	unsigned int port = 8000;

	{
		std::cout << "Your socket's unique_id [default is 123456] or - to skip:" << std::flush;
		std::string unique_id_input;
		std::cin >> unique_id_input;
		if (unique_id_input != "-") {
			std::istringstream stream(unique_id_input);
			stream >> unique_id;
		}
		
	}

	{
		std::ifstream infile("../../.config");
		infile >> address;
		infile >> port;
	}
	
	Settings settings;

	{
		std::cout << "Your delay[default = 1000]: or - to skip:" << std::flush;
		std::string delay_input;
		std::cin >> delay_input;
		if (delay_input != "-") {
			std::istringstream stream(delay_input);
			stream >> settings.report_delay;
		}
	}

	Client client(address, port);

	std::cout << "Client connects to the server: " << address << ":" << port << std::endl;

	bool socket_added = false;

	while (true) {
		if (!socket_added) {
			json payload = {
				{"unique_id", unique_id},
			};
			auto response = client.Post("/api/sockets/add", payload.dump(-1), "application/json");
			if (response && response->status != 200) {
				std::cout << response->body << "\n";
			}

			socket_added = true;
		} else {

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