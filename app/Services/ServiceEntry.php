<?php

namespace App\Services;

class ServiceEntry
{
    /**
     * Entry point
     */
    public static function service($service)
    {
        return self::getService($service);
    }

    /**
     * Get response service base on option or default
     *
     * @param string type
     * @param string name
     *
     * @return class
     */
    public static function getService($service)
    {
        if (class_exists($service)) {
            return new $service;
        }
        return new \App\Services\Types\Emo\DefaultEmoService;
    }
}
