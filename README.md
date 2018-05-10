# Bloomhound

Bloomhound is a system for floral arrangers to easily create and manage proposals, receive reminders for important setup dates, compare flower prices between vendors and more. It is built on Laravel 5 and Vue.js. This project is in active development and is not feature complete.

[Try it out] (http://bloomhound.maxleaver.com/)

## Requirements

[**Composer**](https://getcomposer.org)
PHP dependency manager

A web server of some kind (see Laravel Valet below if you're on OSX).

## Installation

Clone or download the git repository to your local development environment, then run the following:

``` bash
# Install PHP dependencies
composer install

# Install Javascript dependencies, using NPM (or Yarn)
npm install

# Build front-end assets for development
npm run dev
```

## Configuration
Create a .env file in the root directory, using .env.example as a template. Update the database and email server entries to the settings on your server.

Run the following commands:

``` bash
# Install PHP dependencies
composer install

# Generate a unique APP_KEY (see .env.example)
php artisan key:generate

# Populate the database
php artisan migrate

# Generate encryption keys needed to generate secure access tokens
php artisan passport:install

# Create a symbolic link from storage/app/public to public/storage for dynamic, publicly-facing images (ex. user avatars)
php artisan storage:link

# Build front-end assets for development
npm run dev
```

## Running Backend Tests
``` bash
# run all PHP tests
composer test

# run a single test or test class
vendor/bin/phpunit --filter SomeTest
```

## Frontend Build Commands
The following commands are available for building front-end assets:

``` bash
# build for development
npm run dev

# watch files and rebuild for development
npm run watch

# uses polling method to watch files and rebuild for development
npm run watch-poll

# build for production with minification
npm run production
```

## License
This application is licensed under The MIT License (MIT). See the LICENSE file for more details.

## Helpful Tools
[**Laravel Valet**](https://laravel.com/docs/5.4/valet)
Quick development web server

[**Postman**](https://www.getpostman.com)
Testing utility for HTTP requests

[**SourceTree**](https://www.sourcetreeapp.com)
GUI for using Git

[**MailTrap.io**](https://mailtrap.io/)
Free email testing server for development
