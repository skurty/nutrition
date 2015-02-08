<?php

$app->register(new Silex\Provider\MonologServiceProvider(), array(
	'monolog.logfile' => __DIR__.'/../../logs/prod.log',
	'monolog.name'    => 'nutrition'
));

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_mysql',
        'dbname'   => 'nutrition',
        'host'     => 'localhost',
        'user'     => 'root',
        'password' => '',
		'charset'  => 'utf8'
    ),
));