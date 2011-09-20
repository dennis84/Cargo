<?php

/*
 * This file is part of the cargo framework
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

ini_set('display_errors', 1);

require_once __DIR__ . '/../autoload.php';

use Cargo\Application;
use Symfony\Component\HttpFoundation\Request;

$app = new Application();

$app->register(new \Cargo\Extension\ViewExtension());
$app['view']['externalLink'] = 'http://google.de';

$app->registerThemes(array(
    'Foo' => __DIR__ . '/theme',
));

$app->run(Request::createFromGlobals())->send();
