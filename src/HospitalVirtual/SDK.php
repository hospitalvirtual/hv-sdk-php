<?php

namespace HospitalVirtual;


class SDK
{

    private static $apiKey = "";
    private static $production = false;

    /**
     * @param string $apiKey
     */
    public static function setApiKey($apiKey)
    {
        self::$apiKey = $apiKey;
    }

    /**
     * @return string
     */
    public static function getApiKey()
    {
        return self::$apiKey;
    }

    public static function isProduction() {
        return self::$production;
    }
}

?>