<?php
$loader = require __DIR__ . "/../vendor/autoload.php";
$loader->addPsr4('Rarus\\Restart\\', __DIR__ . '/Restart');
date_default_timezone_set('UTC');