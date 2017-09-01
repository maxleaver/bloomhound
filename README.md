# Bloomhound API

API example for Bloomhound

## Requirements

[**Composer**](https://getcomposer.org)
PHP dependency manager

[**PHPUnit**](https://phpunit.de/manual/current/en/installation.html)
PHP testing framework

A web server of some kind (see Laravel Valet below if you're on OSX).

## Installation

After cloning the repository to your local machine, create a .env file in the root directory using .env.example as a template. You should not have to make any changes, unless you want to populate your MailTrap.io info for email testing (see Helpful Tools below).

Run the following commands in your console (assumes you have already installed Composer):

Installs all dependencies
``` bash
composer install

```

Generates a unique APP_KEY
``` bash
php artisan key:generate

```

Generates a unique secret key for generating JWT (JSON Web Tokens)
``` bash
php artisan jwt:secret

```

Builds up the database (a sqlite file in the database directory)
``` bash
php artisan migrate

```

## Usage

Make an HTTP request to your development server using any route in `routes/api.php`.


## Running Tests
``` bash
# runs all tests
phpunit

# runs a single test class
phpunit --filter SomeTest

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

