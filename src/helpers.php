<?php


use Carbon\Carbon;

if (!function_exists('carbon')) {
    /**
     * @param array $params
     *
     * @return \Carbon\Carbon
     * */
    function carbon(...$params)
    {
        if (!$params) {
            return now();
        }

        if ($params[0] instanceof DateTime) {
            return Carbon::instance($params[0]);
        }

        if (is_numeric($params[0]) && (string)(int)$params[0] === (string)$params[0]) {
            return Carbon::createFromTimestamp(...$params);
        }

        return Carbon::parse(...$params);
    }
}
