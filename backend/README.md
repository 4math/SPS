# Backend part for SPS

<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## Backend setup :

- Initialise laravel
```
composer install
```
- Copy .env file
```
cp .env.example .env
```
- Create database in phpmyadmin and give it a name in DB_DATABASE. Also set up ports for laravel and Redis. 
- Create application key
```
php artisan key:generate
```
- Generate JWT key
```
php artisan jwt:secret
```
- Update database after the changes
```
composer dumpautoload
```

- Keep your credentials private!

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
