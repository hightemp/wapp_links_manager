<?php

error_reporting(E_ERROR | E_PARSE);

class Config {
    public static $aOptions = [];

    public static function fnLoad()
    {
        static::$aOptions = json_decode(file_get_contents("./config.json"), true) ?: [];
    }

    public static function fnSave()
    {
        file_put_contents("./config.json", json_encode(static::$aOptions));
    }
}

Config::fnLoad();

define('ROOT_PATH', __DIR__);

$sBase = Config::$aOptions["base"];

$sBA = $sBase."/static/app";
$sB = $sBase."/static/app/jquery-easyui-1.10.2";

$sIP = "/uploads/images";
$sFIP = __DIR__."/uploads/images";
