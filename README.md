# Laravel Alert

Global site message system for **Laravel 5** (For Laravel 4, see the [master](https://github.com/letrunghieu/laravel-alert/tree/master) branch )

## The purpose of this package:

* Generate alerts in many semantic meaning: success, info, warning, error
* Flash collected messages to the next request
* Display collected messages to customizabled view, default to [Bootstrap 3 alert](http://getbootstrap.com/components/#alerts)
* Support icons for each type of message.

## Installation

You will need [Composer](https://getcomposer.org/doc/00-intro.md) to use this package. There is [a nice tutorial](http://code.tutsplus.com/tutorials/easy-package-management-with-composer--net-25530) of using Composer for those who are not familiar with this awesome tool.

Add this to your `composer.json` file.

```json
"hieu-le/laravel-alert": "~2.0"
```

Run `composer update` to update all dependencies. After that, register the service provider for Laravel by adding this line to the `providers` array in `config/app.php`

```php
'HieuLe\Alert\AlertServiceProvider',
```

And add this alias to the `aliases` array in `config/app.php`

```php
'Alert' => 'HieuLe\Alert\Facades\Alert',
```

## Usage

To add new message, use one of these four method. The name of each method is the type of the message you want to add.

```php
Alert::success($message);
# or
Alert::info($message);
# or
Alert::warning($message);
# or
Alert::error($message);
```

To make these message available in next request, you must call `Alert::flash()` at least one time.

```php
Alert::flash()
```

In your view, use the method `Alert::dump()` to display these messages. By default, each type of message is rendered with the format of [Bootstrap 3 alert](http://getbootstrap.com/components/#alerts). You can change they appearance by reading the configuration section below.

## Configuration

You can get more control over this package by modifying these configuations. First of all, publish the package config, so that you can edit the copied version:

```
php artisan vendor:publish
```

Open `config/alert.php` file and change these settings:

* `session_key`: the name of the session key that stored messages between requests. You normally do not want to edit its value
* `icon`: the icon for each type of message when rendered in the default view. You can remove the value of one key to disable the icon for that type of message.
* `view`: the name of the view being used to render each type of message. In the view, you can use 2 variables: `$icon` is the icon of this message type; `$messages`: an array of messages of the current message type

## Work with Laravel validation errors result

To display the validation error, simply add another parameter to the `Alert::dump` method. For example

```php
// In each view, there is a special variable call $errors
// This is the validation errors that is set by calling "withError" method as
// guided at http://laravel.com/docs/5.0/validation#error-messages-and-views

echo Alert::dump($errors->all()); 
```