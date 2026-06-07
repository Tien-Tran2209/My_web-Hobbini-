<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

(new Dotenv())->loadEnv(dirname(__DIR__).'/.env');

if (($_SERVER['APP_ENV'] ?? 'test') === 'test') {
    umask(0000);
}