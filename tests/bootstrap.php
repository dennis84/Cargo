<?php

/*
 * This file is part of the Cargo package.
 *
 * (c) Dennis Dietrich <d.dietrich@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$appDir    = __DIR__.'/..';
$vendorDir = __DIR__.'/../vendor';

require_once $vendorDir.'/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;
use Doctrine\Common\Annotations\AnnotationRegistry;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Symfony'          => $vendorDir,
    'Doctrine\\Common' => $vendorDir.'/doctrine-common/lib',
    'Cargo'            => $appDir.'/src',
    'Cargo\\Test'      => $appDir.'/tests',
    'Acme'             => $appDir.'/example/src',
));

$loader->registerPrefixes(array(
    'Pimple' => $vendorDir.'/pimple/lib',
    'Twig_'  => $vendorDir.'/twig/lib',
));

$loader->register();

AnnotationRegistry::registerLoader(function($class) use ($loader) {
    $loader->loadClass($class);
    return class_exists($class, false);
});

AnnotationRegistry::registerFile(
    $appDir . '/src/Cargo/Annotation/CargoAnnotations.php'
);
