# Backend part for SPS

<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## Backend setup :

-   Initialise laravel

```
composer install
```

-   Copy .env file

```
cp .env.example .env
```

-   Create database in phpmyadmin and give it a name in DB_DATABASE. Also set up ports for laravel and Redis.
-   Create application key

```
php artisan key:generate
```

-   Generate JWT key

```
php artisan jwt:secret
```

-   Update database after the changes

```
composer dumpautoload
```

-   Keep your credentials private!

## Serve the application on the PHP development server :

```
php artisan serve
```

## Serve the application on the local network:

```
php artisan serve --host=0.0.0.0
```

## Run the database migrations :

```
php artisan migrate
```

## Run the application tests :

```
php artisan test
```

## Maintenance :

```
php artisan down	// put into maintenance mode
php artisan up		// bring out of maintenance mode
```

## Help :

```
php artisan list
```

## Project's structure

Main code is located in `app` folder:

-   `Classes/Model` - contains descriptions of the used models in the project.
-   `Http/Controllers` folder:
    -   `AuthController.php` - controls user login, register and JWT distribution.
    -   `Controller.php` - parent class of controllers.
    -   `MeasurementsController.php` - controls addition, deletion and requesting of the data from Smart Power Socket.
    -   `SocketsController.php` - controls addition, deletion, updating of the socket itself and the information about it.
    -   `UsersController.php` - controls deletion and updating for user data.

## API requests routes

They are described in `routes/api.php`.

-   `auth` routes:
    -   POST `api/auth/login` - **{ email: string, password: string }** - handles user logging into the system.
    -   POST `api/auth/register` - **{ name: string, email: string, password }** - handles user registering into the system.
    -   GET `api/auth/refresh` - handles JWT refresh.
-   `sockets` routes:
    -   POST `api/sockets/add` - **{ unique_id: number }** - handles the addition of the new Smart Power Socket.
    -   PUT `api/sockets/connect` - **{ name: string, description?: string, unique_id: number }** - connects new Smart Power Socket from the frontend.
    -   GET `api/sockets/list` - shows all available for a user sockets.
    -   PUT `api/sockets/update-info` - **{ name: string, description?: string }** - updates information about socket itself, e.g. name, description.
    -   PUT `api/sockets/{id}` - **{ switch_state: number(0 or 1) }** - switches the Smart Power Socket on and off.
    -   DELETE `api/sockets/{id}` - deletes the specific socket.
-   `measurements` routes:
    -   POST `api/measurements/add` - **{ power: number }** - adds new measurement to a socket.
    -   GET `api/measurements/list` - shows the list of the measurements for the specific socket.
    -   DELETE `api/measurements/{id}` - deletes specific measurement.
    -   POST `api/measurements/get-period` - **{ time_from: string(ISO Date), time_to: string(ISO Date) }** - gets all measurements in the given interval of time.
