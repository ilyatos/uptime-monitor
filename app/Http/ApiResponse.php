<?php


namespace App\Http;

class ApiResponse
{
    /**
     * Return data as json
     *
     * @param array $responseData
     *
     * @return string
     */
    public static function json($responseData)
    {
        return json_encode($responseData);
    }

    public static function setHeaders($code, $headers = [])
    {
        foreach ($headers as $header) {
            header($header);
        }

        http_response_code($code);
    }

    /**
     * Return data as json with success
     *
     * @param array $data
     * @param mixed $code
     * @param mixed $headers
     *
     * @return string
     */
    public static function success($code, $data = null, $headers = [])
    {
        self::setHeaders($code, $headers);

        $data = [
            'success' => true,
            'response' => $data
        ];

        return self::json($data);
    }

    /**
     * Return data as json with success error
     *
     * @param array $data
     * @param mixed $code
     * @param mixed $headers
     *
     * @return string
     */
    public static function error($code, $data = null, $headers = [])
    {
        self::setHeaders($code, $headers);

        $data = [
            'success' => false,
            'response' => $data
        ];

        return self::json($data);
    }
}
