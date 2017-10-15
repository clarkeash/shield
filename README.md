# Project Moved

This project has moved to https://github.com/laravel-shield/shield.

I am still happy for your contributions please read the guide here: http://laravel-shield.com/contributing





# Shield

<p align="center">
  <a href="https://travis-ci.org/clarkeash/shield">
    <img src="https://img.shields.io/travis/clarkeash/shield.svg?style=flat-square">
  </a>
  <a href="https://codecov.io/gh/clarkeash/shield">
    <img src="https://img.shields.io/codecov/c/github/clarkeash/shield.svg?style=flat-square">
  </a>
  <a href="https://scrutinizer-ci.com/g/clarkeash/shield">
    <img src="https://img.shields.io/scrutinizer/g/clarkeash/shield.svg?style=flat-square">
  </a>
  <a href="https://github.com/clarkeash/shield/blob/master/LICENSE">
    <img src="https://img.shields.io/github/license/clarkeash/shield.svg?style=flat-square">
  </a>
  <a href="https://twitter.com/clarkeash">
    <img src="http://img.shields.io/badge/author-@clarkeash-blue.svg?style=flat-square">
  </a>
</p>

Shield is a laravel middleware to protect against unverified webhooks from 3rd party services.

## Installation

You can pull in the package using [composer](https://getcomposer.org):

```bash
$ composer require clarkeash/shield
```

Publish the package configuration:

```bash
$ php artisan vendor:publish --tag=config
```

## Usage

Create your webhook route in `routes/api.php` and add the middleware like so:

```php
Route::middleware('shield:github')->post('/hooks/github', 'HooksController@github');
```

The name after `shield:` is the name set in `config/shield.php` in the `enabled` section.

## Services

* GitHub
* GitLab
* Stripe
* Zapier

Feel free to submit a PR to support other services.
