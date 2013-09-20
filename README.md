sfRavenPlugin
=============

Enable remote logging to [Sentry](https://getsentry.com/welcome/) into symfony1 applications.

* Fatal errors (syntax error, out of memory)
* Warnings (undefined variable, headers sent, deprecated)
* Exceptions

Install
-------

With composer

    composer require lexpress/sf-raven-plugin

Add to your project configuration:

    # config/ProjectConfiguration.php

    class ProjectConfiguration extends sfProjectConfiguration
    {
      public function setup()
      {
        $this->enablePlugins(array(
          // ...

          'sfRavenPlugin',
        ));
      }
    }

Configure the Raven client. The DSN can be found in the getsentry interface.

    # app/raven.yml

    all:
      client:
        dsn: udp://11111111111111111111111111111111:22222222222222222222222222222222@localhost:9001/1
