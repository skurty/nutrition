<?php

$app['debug'] = true;

$app['exception_handler']->disable();

$app->register(new Silex\Provider\MonologServiceProvider(), array(
	'monolog.logfile' => __DIR__.'/../../logs/test.log',
	'monolog.name'    => 'nutrition'
));

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_mysql',
        'dbname'   => 'nutrition_test',
        'host'     => 'localhost',
        'user'     => 'root',
        'password' => '',
		'charset'  => 'utf8'
    ),
));