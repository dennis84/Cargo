<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;
?>

<?php

require_once __DIR__.'/../vendor/silex.phar';

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\SymfonyBridgesServiceProvider;
use Cargo\Provider\CargoServiceProvider;

$app = new Application();
$app['debug'] = true;

$app['autoloader']->registerNamespaces(array(
    'Cargo'            => __DIR__.'/../src',
    'Symfony'          => __DIR__.'/../vendor',
    'Doctrine\\Common' => __DIR__.'/../vendor/doctrine-common/lib',
));

$app->register(new UrlGeneratorServiceProvider());
$app->register(new SymfonyBridgesServiceProvider(), array(
    'symfony_bridges.class_path' => __DIR__ . '/../vendor',
));

$app->register(new TwigServiceProvider(), array(
    'twig.class_path' => __DIR__ . '/../vendor/twig/lib',
));

$app->register(new CargoServiceProvider(), array(
    'cargo.templating.engine' => 'twig',
    'cargo.cache_path'        => __DIR__ . '/cache',
));

$app['cargo']->registerThemes(array(
    'Acme' => __DIR__.'/theme',
));

$app->run();


$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);

echo 'Page generated in '.$total_time.' seconds.'."\n";
