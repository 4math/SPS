# CMake building for emulator

## Create CMake cache
```
cmake CMakeLists.txt
```

## Build the release file
```
cmake --build . --config release
```

## Set the IP address and port in the **config** file which is in the root of the **emulator** folder

For example:
```
localhost
8000
```

### Access executable in the folder **Release**