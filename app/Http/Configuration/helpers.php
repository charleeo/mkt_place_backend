<?php

function mytest()
{
    echo 'this is helper function';
}

function write_log(array $logMsgArr, $logFile = 'log')
{

    $logDir = base_path() . '/logs/';

    if (!is_dir(base_path('logs'))) {
        mkdir(base_path('logs'));
    }
    $logFile = strtolower($logFile);
    $logFilename = $logDir . "$logFile-" . date('Y-m-d') . '.txt';

    $log_level = '';
    if (!file_exists($logFilename)) {

        $header_level = "(No direct script access allowed)\n\n";
        error_log($header_level, 3, $logFilename);
        chmod($logFilename, 0777);
    }

    //$logMsg = print_r($logMsgArr, true);
    $logMsg = json_encode($logMsgArr);
    error_log($logMsg . PHP_EOL . PHP_EOL, 3, $logFilename);
}

function secondsToTime($seconds)
{
    $dtF = new DateTime('@0');
    $dtT = new DateTime("@$seconds");
    return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes and %s seconds');
}
