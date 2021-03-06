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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

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

use Assetic\Asset\AssetCache;
use Assetic\Cache\FilesystemCache;

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

        $app->before(function () use ($app) {
            $app['twig']->addExtension($app['assetic.twig_extension'], $app['debug']);
        });

        if ($app['debug']) {
            $app->after(function () use ($app) {
                $cache  = new FilesystemCache($app['assetic.cache_path']);
                $writer = new AssetWriter($app['assetic.public_path']);
                $am     = new LazyAssetManager($app['assetic.factory']);
                $loader = new \Twig_Loader_String();

                $am->setLoader('twig', new TwigFormulaLoader($app['twig']));

                foreach ($app['twig.templates'] as $name => $template) {
                    $resource = new TwigResource($loader, $template);
                    $am->addResource($resource, 'twig');
                }

                foreach ($am->getNames() as $name) {
                    $asset = $am->get($name);
                    foreach ($asset as $leaf) {
                        $writer->writeAsset(new AssetCache($leaf, $cache));
                    }
                }
            });
        }
    }

    public function boot(Application $app)
    {
    }
}
