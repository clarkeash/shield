![shield](https://user-images.githubusercontent.com/1612186/28499822-09510182-6fbf-11e7-9c43-bb70fa9c89b0.png)


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
