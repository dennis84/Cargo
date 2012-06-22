<?php

ini_set('display_errors', 1);

require_once __DIR__.'/../../vendor/autoload.php';

$app = new \Silex\Application();
$app['debug'] = true;

$app->register(new \Silex\Provider\TwigServiceProvider());

$app->register(new \Cargo\Provider\CargoServiceProvider(), array(
    //'cargo.cache_dir' => __DIR__ . '/../cache',
));

$app->register(new \Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../monolog.txt',
));

$app->register(new \Silex\Provider\UrlGeneratorServiceProvider());

$app->register(new \Silex\Provider\SessionServiceProvider());
$app['session']->start();

$app->register(new \Silex\Provider\SecurityServiceProvider());

$app['security.firewalls'] = array(
    'main' => array(
        'form'    => array('login_path' => '/login', 'check_path' => '/admin/login_check'),
        'users'   => array(
            'admin' => array('ROLE_ADMIN', '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg=='),
        ),
        'anonymous' => true,
    ),
);

$app['cargo']->registerThemes(array(
    'admin'   => __DIR__.'/../themes/admin',
    'website' => __DIR__.'/../themes/website',
    'wiki'    => __DIR__.'/../themes/wiki',
));

$app->run();
