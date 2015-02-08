<?php

defined('APPLICATION_ENV')
    || define('APPLICATION_ENV',
              (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV')
                                         : 'production'));

require_once __DIR__.'/../app/bootstrap.php';

$app = require __DIR__.'/../app/app.php';

if (APPLICATION_ENV == 'production') {
    require __DIR__.'/../app/config/prod.php';
} else {
    require __DIR__.'/../app/config/dev.php';
}

require __DIR__.'/../app/controller.php';

$app->run();