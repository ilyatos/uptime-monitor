<?php

namespace Monitor\Modules;

use Monitor\Helpers\ResponseFromService;

class HttpStatusCode
{
    const NO_CODE_MESSAGE = 'HTTP code not found';

    private $response;

    /**
     * http_code => header_like_value
     *
     * @var array
     */
    private $codeStatus = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'No error',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version Not Supported',
    ];

    /**
     * HttpStatusCode constructor.
     *
     * @param ResponseFromService $response
     */
    public function __construct(ResponseFromService $response)
    {
        $this->response = $response;
    }

    /**
     * Match compared value to code.
     *
     * @param string $pattern
     * @return bool
     */
    public function match(string $pattern): bool
    {
        return preg_match('/' . $pattern . '/', $this->response->getHttpCode());
    }

    /**
     * Return name of a given code.
     *
     * @return string
     */
    public function getCodeName(): string
    {
        if (!array_key_exists($this->response->getHttpCode(), $this->codeStatus)) {
            return self::NO_CODE_MESSAGE;
        }

        return $this->codeStatus[$this->response->getHttpCode()];
    }
}
