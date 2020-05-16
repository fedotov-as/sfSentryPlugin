<?php

/**
 * Class Sentry
 *
 * @author Aleksandr Fedotov <mezonix@gmail.com>
 */
final class Sentry
{
    /**
     * Sending message to Sentry
     *
     * @param string $message Message text
     * @param string $level   Message level
     * @param mixed  $vars    User variables
     */
    private static function sendMessage($message, $level, $vars = null)
    {
        sfSentry::getInstance()->captureMessage($message, array(), $level, $vars);
    }

    /**
     * Sending debug message to Sentry
     *
     * @param string $message Message text
     * @param mixed  $vars    User variables
     */
    public static function sendDebug($message, $vars = null)
    {
        self::sendMessage($message, sfSentry::DEBUG, $vars);
    }

    /**
     * Sending info message to Sentry
     *
     * @param string $message Message text
     * @param mixed  $vars    User variables
     */
    public static function sendInfo($message, $vars = null)
    {
        self::sendMessage($message, sfSentry::INFO, $vars);
    }

    /**
     * Sending warning message to Sentry
     *
     * @param string $message Message text
     * @param mixed  $vars    User variables
     */
    public static function sendWarn($message, $vars = null)
    {
        self::sendMessage($message, sfSentry::WARN, $vars);
    }

    /**
     * Sending error message to Sentry
     *
     * @param string $message Message text
     * @param mixed  $vars    User variables
     */
    public static function sendError($message, $vars = null)
    {
        self::sendMessage($message, sfSentry::ERROR, $vars);
    }

    /**
     * Sending exception to Sentry
     *
     * @param Exception $exception Exception object
     * @param mixed     $vars      User variables
     */
    public static function sendException($exception, $vars = null)
    {
        sfSentry::getInstance()->captureException($exception, $vars);
    }
}
