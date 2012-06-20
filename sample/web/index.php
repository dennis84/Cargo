<?php

ini_set('display_errors', 1);

require_once __DIR__.'/../../vendor/autoload.php';

$app = new \Silex\Application();
$app['debug'] = true;

$app->register(new \Silex\Provider\TwigServiceProvider());

$app->register(new \Cargo\Provider\CargoServiceProvider(), array(
    'cargo.cache_dir' => __DIR__ . '/../cache',
));

$app['cargo']->registerThemes(array(
    'website' => __DIR__.'/../themes/website',
    'wiki'    => __DIR__.'/../themes/wiki',
));

$app->error(function (\Exception $e, $code) {
  echo $e->getMessage();
  echo $code;
});

$app->run();
