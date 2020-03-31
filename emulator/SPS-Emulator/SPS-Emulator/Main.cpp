#include "httplib.h"
using namespace httplib;

#include "json.hpp"
using json = nlohmann::json;

#include <iostream>
#include <string>
#include <chrono>
#include <thread>

int main() {
	std::string UNIQUE_ID("12345asdfg");

	Client client("127.0.0.1", 1357);

	while (true) {
		json data_body_json = {
			{"Sender", UNIQUE_ID},
			{"Data", rand() % 1000}
		};

		Headers headers = {
			{"Unique-ID", UNIQUE_ID}
		};

		auto status_response = client.Get("/status", headers);
		if (status_response && status_response->status == 200) {
			json status_json = json::parse(status_response->body);
			std::cout << status_json.dump(1, '\t');
		}

		auto data_response = client.Post("/data", headers, data_body_json.dump(-1), "application/json");
		std::this_thread::sleep_for(std::chrono::seconds(2));
	}

	return 0;
}