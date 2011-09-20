<?php

/*
 * This file is part of the cargo framework
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

if (false === class_exists('Symfony\Component\ClassLoader\UniversalClassLoader', false)) {
    require_once __DIR__.'/vendor/Symfony/Component/ClassLoader/UniversalClassLoader.php';
}

use Symfony\Component\ClassLoader\UniversalClassLoader;
use Doctrine\Common\Annotations\AnnotationRegistry;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Symfony'          => __DIR__.'/vendor',
    'Doctrine\\Common' => __DIR__.'/vendor/doctrine-common/lib',
    'Cargo'            => __DIR__.'/src',
    'Acme'             => __DIR__.'/example/src',
));

$loader->registerPrefixes(array(
    'Pimple' => __DIR__.'/vendor/pimple/lib',
    'Twig_'  => __DIR__.'/vendor/twig/lib',
));

$loader->register();

AnnotationRegistry::registerLoader(function($class) use ($loader) {
    $loader->loadClass($class);
    return class_exists($class, false);
});

AnnotationRegistry::registerFile(
    __DIR__ . '/src/Cargo/Annotation/CargoAnnotations.php'
);
