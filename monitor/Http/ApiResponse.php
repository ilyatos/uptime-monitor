<?php


namespace Monitor\Http;


class ApiResponse
{
    /**
     * Return json encoded data
     *
     * @param array $data
     * @return string
     */
    public static function json($responseData)
    {
        return json_encode($responseData);
    }

    public static function setHeaders($code = null, $headers = [])
    {
        foreach ($headers as $header) {
            header($header);
        }

        if ($code !== null) { http_response_code($code); };
    }

    /**
     * Return json encoded data with success = true
     *
     * @param array $data
     * @return string
     */
    public static function success($data, $code = null, $headers = [])
    {
        self::setHeaders($code, $headers);

        $data = [
            'success' => true,
            'response' => $data
        ];

        return self::json($data);
    }

    /**
     * Return json encoded data with success = false
     *
     * @param array $data
     * @return string
     */
    public static function error($data, $code = null, $headers = [])
    {
        self::setHeaders($code, $headers);

        $data = [
            'success' => false,
            'response' => $data
        ];

        return self::json($data);
    }

}