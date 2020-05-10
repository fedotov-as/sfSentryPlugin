sfSentryPlugin
=============

Enable remote logging to [Sentry](https://getsentry.com/welcome/) into Symfony1 applications.

* Fatal errors (syntax error, out of memory)
* Warnings (undefined variable, headers sent, deprecated)
* Exceptions

Install
-------

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

Configure the Sentry client. The DSN can be found in the GetSentry interface.

````yaml
# config/sentry.yml

all:
  client:
    dsn: http://public@sentry.example.com:9000/[PROJECT_ID]
````
