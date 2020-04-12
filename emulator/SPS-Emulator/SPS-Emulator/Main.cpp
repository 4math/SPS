#include "include/httplib.h"
using namespace httplib;

#include "include/json.hpp"
using json = nlohmann::json;

#include <iostream>
#include <string>
#include <chrono>
#include <thread>

struct Settings {
	bool enabled = true;
	long long report_delay = 2000;
};

int main() {
	std::string UNIQUE_ID("12345asdfg");

	Settings settings;

	Client client("127.0.0.1", 1357);

	while (true) {
		json data_body_json = {
			{"Data", rand() % 1000}
		};

		Headers headers = {
			{"Unique-ID", UNIQUE_ID}
		};

		auto status_response = client.Get("/status", headers);
		if (status_response && status_response->status == 200) {
			json status_json = json::parse(status_response->body);
			std::cout << status_json.dump(1, '\t');

			if (status_json["Enabled"].is_boolean())
				settings.enabled = status_json["Enabled"].get<bool>();
			else
				std::cout << "'Enabled' object is null or not boolean\n";

			if (status_json["Report-Delay"].is_number_integer())
				settings.report_delay = status_json["Report-Delay"].get<long long>();
			else
				std::cout << "'Report-Delay' object is null or not float\n";
		}

		auto data_response = client.Post("/data", headers, data_body_json.dump(-1), "application/json");
		std::this_thread::sleep_for(std::chrono::milliseconds(settings.report_delay));
	}

	return 0;
}