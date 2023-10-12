<?php

namespace App\Http\Configuration;


/**
 * 
 * a utility class with methods for common http tasks
 */
final class AppHttpUtils
{

    /**
     * 
     * get array that follows API response data format for this project
     * 
     * @param string $message response message
     * @param bool $status boolean for success or failure
     * @param array $data response data
     * 
     * @return array
     */
    public static function responseStructure(string $message, bool $status = true, $data = null)
    {
        return [
            'status' => $status,
            'response' => $data,
            'message' => $message,
        ];
    }
}
