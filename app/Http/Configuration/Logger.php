<?php

namespace App\Http\Configuration;

use Illuminate\Http\Request;
use Throwable;

final class Logger
{
    public static function getLogData(Request $request, $response, $serviceName, $extra = null): array
    {

        $logData = [];

        $logData['header'] = $request->header();
        // remove the auth token from the log data
        $logData['header']['authorization'] = '############...*...secret...*...############';

        $logData['method'] = $request->method();
        $logData['url'] = $request->fullUrl();
        $logData['_Service'] = $serviceName;
        $logData['Request'] = array('time' => date('Y-m-d H:i:s'), 'data' => $request->all());
        $logData['Response'] = array('time' => date('Y-m-d H:i:s'), 'data' => $response);

        $logData['extra'] = $extra;

        return $logData;
    }

    public static function errorLog(Throwable $e)
    {
        return [
            'exception ' => '---------------------------->',
            'msg' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ];
    }
}
