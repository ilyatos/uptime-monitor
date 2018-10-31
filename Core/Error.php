<?php

namespace Core;

class Error
{
    /**
     * Error handler. Converts all errors to Exceptions by throwing an ErrorException.
     *
     * @param int    $level   Error level
     * @param string $message Error message
     * @param string $file    Filename where the error was raised
     * @param int    $line    Line number in the file
     *
     * @throws \ErrorException
     */
    public static function errorHandler($level, $message, $file, $line)
    {
        if (error_reporting() !== 0) {
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }

    /**
     * Exception handler
     *
     * @param \Exception $exception The exception
     */
    public static function exceptionHandler($exception)
    {
        $log = ROOT . '/logs/' . date('d-m-Y') . '.txt';

        ini_set('error_log', $log);

        $message = "Uncaught exception: '" . get_class($exception) . "'" . PHP_EOL;
        $message .= "Message: '" . $exception->getMessage() . "'" . PHP_EOL;
        $message .= "Stack trace: " . PHP_EOL . $exception->getTraceAsString() . PHP_EOL;
        $message .= "Thrown in '" . $exception->getFile() . "' on line " .
            $exception->getLine() . PHP_EOL;

        error_log($message);
    }
}
