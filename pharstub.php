<?php

use Freezemage\Smoke\Main;


if (Phar::isValidPharFilename(__FILE__)) {
    $root = __FILE__;
    /** @noinspection PhpIncludeInspection */
    include_once "phar://$root/vendor/autoload.php";
} else {
    include_once __DIR__ . '/vendor/autoload.php';
}


$main = new Main();
$main->run();

__HALT_COMPILER();