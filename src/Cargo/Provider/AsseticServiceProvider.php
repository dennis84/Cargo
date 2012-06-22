<?php

/*
 * This file is part of the cargo framework
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cargo\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

use Assetic\AssetManager;
use Assetic\FilterManager;
use Assetic\Factory\AssetFactory;
use Assetic\Extension\Twig\AsseticExtension;
use Assetic\Filter\Yui\CssCompressorFilter;
use Assetic\Filter\Yui\JsCompressorFilter;
use Assetic\Filter\GoogleClosure\CompilerJarFilter;

use Assetic\AssetWriter;
use Assetic\Extension\Twig\TwigFormulaLoader;
use Assetic\Extension\Twig\TwigResource;
use Assetic\Factory\LazyAssetManager;


/** 
 *  AsseticServiceProvider.
 *
 *  @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class AsseticServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['assetic.asset_manager'] = $app->share(function () use ($app) {
            return new AssetManager();
        });

        $app['assetic.filter_manager'] = $app->share(function () use ($app) {
            $filterManager = new FilterManager();
            $filterManager->set('yui_css', new CssCompressorFilter($app['assetic.yui_path']));
            $filterManager->set('yui_js', new JsCompressorFilter($app['assetic.yui_path']));

            return $filterManager;
        });

        $app['assetic.factory'] = $app->share(function () use ($app) {
            $factory = new AssetFactory($app['assetic.asset_path'], $app['debug']);
            $factory->setAssetManager($app['assetic.asset_manager']);
            $factory->setFilterManager($app['assetic.filter_manager']);

            return $factory;
        });

        $app['assetic.twig_extension'] = $app->share(function () use ($app){
            return new AsseticExtension($app['assetic.factory']);
        });

        $oldTwig = $app->raw('twig');
        $app['twig'] = $app->share(function($c) use ($oldTwig, $app) {
            $twig = $oldTwig($c);
            $twig->addExtension($app['assetic.twig_extension']);

            return $twig;
        });

        $app['assetic.asset_writer'] = $app->share(function () use ($app) {
            $am = new LazyAssetManager($app['assetic.factory']);

            // enable loading assets from twig templates
            $am->setLoader('twig', new TwigFormulaLoader($app['twig']));

            foreach ($app['twig.templates'] as $template) {
                $resource = new TwigResource($app['twig.loader'], $template);
                $am->addResource($resource, 'twig');
            }


            $writer = new AssetWriter($app['assetic.public_path']);
            $writer->writeManagerAssets($am);
        });

    }

    public function boot(Application $app)
    {
        $app['assetic.asset_writer'];
    }
}
