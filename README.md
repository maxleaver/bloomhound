# Bloomhound API

API example for Bloomhound

## Requirements

[**Composer**](https://getcomposer.org)
PHP dependency manager

A web server of some kind (see Laravel Valet below if you're on OSX).

## Installation

Clone the repository to your local machine.

Create a .env file in the root directory, using .env.example as a template. Update the database and email server entries to the settings on your server.

Install Composer if you haven't already, then run the following in your console:

Install all dependencies
``` bash
composer install

```

Generate a unique APP_KEY
``` bash
php artisan key:generate

```

Build the database (a sqlite file in the database directory)
``` bash
php artisan migrate

```

Create the encryption keys needed to generate secure access tokens
``` bash
php artisan passport:install

```

Create a symbolic link from storage/app/public to public/storage for dynamic, publicly-facing images (ex. user avatars)
``` bash
php artisan storage:link

```

## Usage

Make an HTTP request to your development server using any route in `routes/api.php`.


## Running Tests
``` bash
# run all tests
composer test

# run a single test or test class
vendor/bin/phpunit --filter SomeTest

```

## Helpful Tools
[**Laravel Valet**](https://laravel.com/docs/5.4/valet)
Quick development web server

[**Postman**](https://www.getpostman.com)
Testing utility for HTTP requests

[**SourceTree**](https://www.sourcetreeapp.com)
GUI for using Git

[**MailTrap.io**](https://mailtrap.io/)
Free email testing server for development
