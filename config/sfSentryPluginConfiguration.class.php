<?php

/**
 * Class sfSentryPluginConfiguration
 */
class sfSentryPluginConfiguration extends sfPluginConfiguration
{
    const CONFIG_PATH = 'config/sentry.yml';

    protected $errorHandler;

    /**
     * Initializes the plugin.
     *
     * This method is called after the plugin's classes have been added to sfAutoload.
     */
    public function initialize()
    {
        if ($this->configuration instanceof sfApplicationConfiguration) {
            require $this->configuration->getConfigCache()->checkConfig(self::CONFIG_PATH);

            $dsn = sfConfig::get('sentry_client_dsn', sfConfig::get('sentry_dsn')); // Keep BC
            $options = sfConfig::get('sentry_client_options', array());
            if (!$dsn) {
                return;
            }

            $client = new sfSentryClient($dsn, $options);

            $this->errorHandler = new Raven_ErrorHandler($client);
            $this->errorHandler->registerExceptionHandler();
            $this->errorHandler->registerErrorHandler(true, E_ALL | E_STRICT);
            $this->errorHandler->registerShutdownFunction(500);

            $this->dispatcher->connect('application.throw_exception', array($this, 'listenToExceptions'));
        }
    }

    /**
     * Listener to exceptions
     * @param $event
     */
    public function listenToExceptions($event)
    {
        $this->errorHandler->handleException($event->getSubject());
    }
}
