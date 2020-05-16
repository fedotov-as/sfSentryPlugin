# sfSentryPlugin

[![Total Downloads](https://img.shields.io/packagist/dt/mezonix/sf-sentry-plugin.svg?style=flat-square)](https://packagist.org/packages/mezonix/sf-sentry-plugin)
[![Months Downloads](https://img.shields.io/packagist/dm/mezonix/sf-sentry-plugin.svg?style=flat-square)](https://packagist.org/packages/mezonix/sf-sentry-plugin)
[![Latest Stable Version](https://img.shields.io/packagist/v/mezonix/sf-sentry-plugin.svg?style=flat-square)](https://packagist.org/packages/mezonix/sf-sentry-plugin)
[![License](https://img.shields.io/packagist/l/mezonix/sf-sentry-plugin.svg?style=flat-square)](https://packagist.org/packages/mezonix/sf-sentry-plugin)

Enable remote logging to [Sentry](https://getsentry.com/welcome/) into Symfony1 applications.

The plugin can log events:
* Fatal errors (syntax error, out of memory)
* Warnings (undefined variable, headers sent, deprecated)
* Exceptions
* User messages

## Requirements

* PHP ≥ 5.3
* Symfony ≥ 1.4
* Sentry instance

## Installation

With composer

    composer require mezonix/sf-sentry-plugin

Add to your project configuration:

````php
# config/ProjectConfiguration.php

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enablePlugins(array(
      // ...
      'sfSentryPlugin',
    ));
  }
}
````

Configure the Sentry client. The DSN can be found in the Sentry interface.

````yaml
# config/sentry.yml

all:
  client:
    dsn: http://public@sentry.example.com:9000/[PROJECT_ID]
    options:
      release: ~
      exclude:
        - sfStopException
      auto_log_stacks: true
````

 * `dsn` - Sentry connection URL.
 * `options/release` - Release version or tag name.
 * `options/exclude` - List of exception classes that are ignored.
 * `options/auto_log_stacks` -  Generates a backtrace. See [debug-backtrace](https://php.net/manual/en/function.debug-backtrace.php).

## Usage

#### Sentry

````php
// send debug message
Sentry::sendDebug('Debug message text');

// send information message
Sentry::sendInfo('Information message text');

// send warning message
Sentry::sendWarn('Warning message text');

// send error message
Sentry::sendError('Error message text');

// send error message with variables
Sentry::sendError('Error message text', array('foo' => 'bar'));

// send exception
Sentry::sendException(new Exception('Exception message'));

// send exception with variables
Sentry::sendException(new Exception('Exception message'), array('foo' => 'bar'));
````
