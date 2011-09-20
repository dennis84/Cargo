<?php

/*
 * This file is part of the cargo framework
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cargo\Extension;

use Cargo\Application;

use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\Loader\ArrayLoader;

/**
 * TranslationExtension.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class TranslationExtension implements ExtensionInterface
{
    /**
     * Registers an extension.
     *
     * @param Application $app An appllication instance
     */
    public function register(Application $app)
    {
        $app['translator.messages'] = array();

        $app['translator'] = $app->share(function () use ($app) {
            $translator = new Translator(
                $app->has('locale') ? $app['locale'] : 'en',
                $app['translator.message_selector']
            );

            if ($app->has('locale_fallback')) {
                $translator->setFallbackLocale($app['locale_fallback']);
            }

            $translator->addLoader('array', $app['translator.loader']);
            foreach ($app['translator.messages'] as $locale => $messages) {
                $translator->addResource('array', $messages, $locale);
            }

            return $translator;
        });

        $app['translator.loader'] = $app->share(function () {
            return new ArrayLoader();
        });

        $app['translator.message_selector'] = $app->share(function () {
            return new MessageSelector();
        });

        if ($app->has('translation.class_path')) {
            $app['autoloader']->registerNamespace(
                'Symfony\\Component\\Translation',
                $app['translation.class_path']
            );
        }
    }
}
