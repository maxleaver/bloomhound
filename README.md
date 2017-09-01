# Bloomhound API

API example for Bloomhound

## Installation

Clone the repository to your local machine. Use .env.example as a template to create a .env file in the root directory. You should not have to make any changes, unless you want to populate your MailTrap.io info for email testing (see Helpful Tools below). Run the following (assumes you have Composer installed already):

``` bash
composer install

```

Generate a unique APP_KEY by running:
``` bash
php artisan key:generate

```

Generate a unique JWT by running:
``` bash
php artisan jwt:secret

```

Build up the database (currently a simple sqlite file):
``` bash
php artisan migrate

```

## Usage

Simply make an HTTP request to your development server using any route in `routes/api.php`.

``` bash
# run all tests
phpunit

# run single test class
phpunit --filter SomeTest

```

## Helpful Tools

[**Composer**](https://getcomposer.org)
PHP dependency manager

[**PHPUnit](https://phpunit.de/manual/current/en/installation.html)
PHP testing framework

[**Laravel Valet**](https://laravel.com/docs/5.4/valet)
Quick development web server

[**Postman**](https://www.getpostman.com)
Testing utility for HTTP requests

[**SourceTree**](https://www.sourcetreeapp.com)
GUI for using Git

[**MailTrap.io**](https://mailtrap.io/)
Free email testing server for development

