# Laravel Tokens
PHP implementation of https://github.com/fnando/tokens.

[![PHP from Packagist](https://img.shields.io/packagist/php-v/forthelocal/laravel-tokens.svg)]()
[![Packagist](https://img.shields.io/packagist/l/doctrine/orm.svg)]()

[![Build Status](https://travis-ci.org/forthelocal/laravel-tokens.svg?branch=master)](https://travis-ci.org/forthelocal/laravel-tokens) 
[![codecov](https://codecov.io/gh/forthelocal/laravel-tokens/branch/master/graph/badge.svg)](https://codecov.io/gh/forthelocal/laravel-tokens)

## Usage

### Installation
```bash
composer require forthelocal/laravel-tokens
```

```php
class User extends Model
{
    use Tokenizable;
}

// create a new user; remember that the token requires an existing record
// because it depends on its id
$user = User::create();

// create token that never expires
$user->addToken("activate");

// uses custom expires_at
user->addToken("valid", ["expires_at" => strtotime('1 day', time())]);

// uses the default size (48 characters)
$user->addToken("activate");

// uses custom size (up to 240)
$user->addToken("activate", ["length" => 120]);

// uses custom token value
$user->addToken("activate", ["token" => "abc123"]);

// create token with arbitrary data.
$user->addToken("activate", ["data" => [ "action" => "do something"]]);

// find token by name
$user->findTokenByName("reset_account");

// find valid token per user context.
$user->findValidToken("reset_account", "ea2f14aeac40");

// check if a token has expired
$user->tokens()->first()->isExpired();

// find user by token
User::findByToken("activate", "ea2f14aeac40");

// remove all expired tokens except those with NULL values
Token::clean();

// generate a token as string, without saving it
User::generateToken();

// remove a token by its name
$user->removeToken("activate");

// find user by valid token (same name, same hash, not expired)
User::findByValidToken("activate", "ea2f14aeac40");

// Token hash
echo $token; //=> ea2f14aeac40
```

# License
MIT