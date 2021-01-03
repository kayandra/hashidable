# Hashidable

> Hashidable uses [hashids](https://hashids.org/) to obfuscate Laravel route ids.

## Installation

_Note: This package is built to work with Laravel versions greater than 7. It may work in older version, but this has not been tested._

```
composer require kayandra/hashidable
```

## Setup

Import the `Hashidable` trait and add it to your model.

```php
use Kayandra\Hashidable\Hashidable;

Class User extends Model
{
  use Hashidable;
}
```

## Usage

```php
$user = User::find(1);

$user->id; // 1
$user->hashid; // 3RwQaeoOR1E7qjYy

User::find(1);
User::findByHashId('3RwQaeoOR1E7qjYy');
User::findByHashidOrFail('3RwQaeoOR1E7qjYy');
```

### Route Model Binding

Assuming we have a route resource defined as follows:

```php
Route::apiResource('users', UserController::class);
```

This package does not affect route model bindings, the only difference is, instead of placing the id in the generated route, it uses the hashid instead.

So, `route('users.show', $user)` returns `/users/3RwQaeoOR1E7qjYy`;

When you define your controller that auto-resolves a model in the parameters, it will work as always.

```php
public function show(Request $request, User $user)
{
  return $user; // Works just fine
}
```

## Configuring

First, publish the config file using:

```
php artisan vendor:publish --tag=hashidable.config
```

The available configuration options are:

```php
return [
    /**
     * Ensures hashids are unique to project. Preferrably, use a value
     * that won't change during the project's lifetime.
     */
    'salt' => hash('sha256', 'chitty chitty bang bang'),

    /**
     * Length of the generated hashid.
     */
    'length' => 16,

    /**
     * Character set used to generate the hashids.
     */
    'charset' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890',

    /**
     * Prefix attached to the generated hash.
     */
    'prefix' => '',

    /**
     * Suffix attached to the generated hash.
     */
    'suffix' => '',

    /**
     * If a prefix of suffix is defined, we use this as a separator
     * between the prefix/suffix.
     */
    'separator' => '-',
];
```

### Per-Model Configuration

You can also extend the global configuration on a per-model basis. To do this, your model should implement the `Kayandra\Hashidable\HashidableConfig` and define the `hashidableConfig()` method on the model.

This method returns an array or subset of options similar to the global configuration.

```php 
public function hashidableConfig()
{
    return ['prefix' => 'app'];
}
```

## FAQs

<details>
  <summary>Where are the generated hashes stored?</summary>

Hashidable does not touch the database to store any sort of metadata. What it does instead is use an internal encoder/decoder to dynamically calculate the hashes from the models' id.

</details>

## License

[MIT](/LICENSE.md)
