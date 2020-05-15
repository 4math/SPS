cmake_minimum_required(VERSION 3.0)

include_directories("../vs/SPS-Emulator/include")
project(emulator)

set(SOURCE "../vs/SPS-Emulator/Main.cpp")

add_executable(${PROJECT_NAME} ${SOURCE})