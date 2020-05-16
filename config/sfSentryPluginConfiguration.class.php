<?php

/**
 * Class sfSentryPluginConfiguration
 */
class sfSentryPluginConfiguration extends sfPluginConfiguration
{
    const CONFIG_PATH = 'config/sentry.yml';

    /**
     * Initializes the plugin.
     *
     * This method is called after the plugin's classes have been added to sfAutoload.
     */
    public function initialize()
    {
        if ($this->configuration instanceof sfApplicationConfiguration) {
            require $this->configuration->getConfigCache()->checkConfig(self::CONFIG_PATH);

            $dsn = sfConfig::get('sentry_client_dsn');
            $options = sfConfig::get('sentry_client_options', array());

            sfSentry::getInstance()->configure($dsn, $options);
            $this->dispatcher->connect('application.throw_exception', array($this, 'listenToExceptions'));
        }
    }

    /**
     * Listener to exceptions
     *
     * @param sfEvent $event
     *
     * @throws Exception
     */
    public function listenToExceptions(sfEvent $event)
    {
        $instance = sfSentry::getInstance();
        if (false === $instance->getStatus()) {
            return;
        }
        $instance->handleException($event->getSubject());
    }
}
