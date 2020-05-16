<?php

/**
 * Class sfSentry
 *
 * @author Aleksandr Fedotov <mezonix@gmail.com>
 */
class sfSentry
{
    const DEBUG = Raven_Client::DEBUG;
    const INFO = Raven_Client::INFO;
    const WARN = Raven_Client::WARN;
    const ERROR = Raven_Client::ERROR;

    /**
     * @var sfSentry
     */
    private static $instance;

    /**
     * @var bool
     */
    private $status = false;

    /**
     * @var sfSentryClient
     */
    private $client;

    /**
     * @var Raven_ErrorHandler
     */
    private $errorHandler;

    /**
     * Retrieves the singleton instance of this class.
     *
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
    public function getStatus()
    {
        return (bool)$this->status;
    }

    /**
     * @param bool $status
     */
    private function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Configure Sentry instance
     *
     * @param string $dsn     Data source name
     * @param array  $options Configuration options
     */
    public function configure($dsn, $options)
    {
        if (empty($dsn)) {
            $this->setStatus(false);
            return;
        }
        $this->client = new sfSentryClient($dsn, $options);
        $this->errorHandler = new Raven_ErrorHandler($this->client);
        $this->errorHandler->registerExceptionHandler();
        $this->errorHandler->registerErrorHandler(true, E_ALL | E_STRICT);
        $this->errorHandler->registerShutdownFunction(1024);
        $this->setStatus(true);
    }

    /**
     * Handler Exception
     *
     * @param Exception $e
     * @param array     $vars
     *
     * @throws Exception
     */
    public function handleException($e, $vars = null)
    {
        if (false === $this->getStatus()) {
            return;
        }
        $this->errorHandler->handleException($e, false, $vars);
    }

    /**
     * Log a message to sentry
     *
     * @param string     $message The message (primary description) for the event.
     * @param array      $params  Params to use when formatting the message.
     * @param string     $level   Log level group
     * @param bool|array $stack   Stack trace
     * @param mixed      $vars    User variables
     */
    public function captureMessage($message, $params = array(), $level = self::INFO, $stack = false, $vars = null)
    {
        if (false === $this->getStatus()) {
            return;
        }
        $this->client->captureMessage($message, $params, $level, $stack, $vars);
    }

    /**
     * Log an exception to sentry
     *
     * @param Exception $exception The Throwable/Exception object
     * @param mixed     $vars      User variables
     */
    public function captureException($exception, $vars = null)
    {
        if (false === $this->getStatus()) {
            return;
        }
        $this->client->captureException($exception, null, null, $vars);
    }
}
