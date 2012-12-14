<?php

class sfRavenPluginConfiguration extends sfPluginConfiguration
{
  const CONFIG_PATH = 'config/raven.yml';

  protected $errorHandler;

  /**
   * Initializes the plugin.
   *
   * This method is called after the plugin's classes have been added to sfAutoload.
   */
  public function initialize()
  {
    if ($this->configuration instanceof sfApplicationConfiguration)
    {
      $configCache = $this->configuration->getConfigCache();
      $configCache->registerConfigHandler(self::CONFIG_PATH, 'sfDefineEnvironmentConfigHandler', array(
        'prefix' => 'raven_',
      ));

      require $configCache->checkConfig(self::CONFIG_PATH);

      if (!sfConfig::get('raven_dsn'))
      {
        return;
      }

      $client = new sfRavenClient(sfConfig::get('raven_dsn'));

      $this->errorHandler = new Raven_ErrorHandler($client);
      $this->errorHandler->registerExceptionHandler();
      $this->errorHandler->registerErrorHandler(true, E_ALL | E_STRICT);
      $this->errorHandler->registerShutdownFunction(500);

      $this->dispatcher->connect('application.throw_exception', array($this, 'listenToExceptions'));
    }
  }

  public function listenToExceptions($event)
  {
    $this->errorHandler->handleException($event->getSubject());
  }
}
