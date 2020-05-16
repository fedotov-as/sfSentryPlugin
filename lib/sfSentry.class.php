<?php

/**
 * Class sfSentry
 * @author Aleksandr Fedotov <mezonix@gmail.com>
 */
class sfSentry
{
    /**
     * @var sfSentry
     */
    private static $instance;

    /**
     * @var bool
     */
    private $available = false;

    /**
     * @var Raven_ErrorHandler
     */
    protected $errorHandler;

    /**
     * Retrieves the singleton instance of this class.
     * @return sfSentry
     */
    static public function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new sfSentry();
        }
        return self::$instance;
    }

    /**
     * @return bool
     */
    public function isAvailable()
    {
        return $this->available;
    }

    /**
     * @param string $dsn
     * @param array $options
     */
    public function init($dsn, $options)
    {
        if (empty($dsn)) {
            $this->available = false;
            return;
        }
        $client = new sfSentryClient($dsn, $options);
        $this->errorHandler = new Raven_ErrorHandler($client);
        $this->errorHandler->registerExceptionHandler();
        $this->errorHandler->registerErrorHandler(true, E_ALL | E_STRICT);
        $this->errorHandler->registerShutdownFunction(1024);
        $this->available = true;
    }

    /**
     * Handler Exception
     * @param Exception $e
     * @param bool $isError
     * @param mixed $vars
     * @throws Exception
     */
    public function handleException($e, $isError = false, $vars = null)
    {
        if (false === $this->isAvailable()) {
            return;
        }
        $this->errorHandler->handleException($e, $isError, $vars);
    }
}
