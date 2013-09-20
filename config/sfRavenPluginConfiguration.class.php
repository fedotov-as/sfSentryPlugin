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

      $dsn     = sfConfig::get('raven_client_dsn', sfConfig::get('raven_dsn')); // Keep BC
      $options = sfConfig::get('raven_client_options', array());
      if (!$dsn)
      {
        return;
      }

      $client = new sfRavenClient($dsn, $options);

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
