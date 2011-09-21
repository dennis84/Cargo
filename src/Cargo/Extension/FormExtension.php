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
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\DefaultCsrfProvider;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\SessionCsrfProvider;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\Extension\Core\CoreExtension;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension as FormValidatorExtension;
use Symfony\Component\Form\Extension\Csrf\CsrfExtension;

/**
 * FormExtension.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class FormExtension implements ExtensionInterface
{
    /**
     * Registers an extension.
     *
     * @param Application $app An appllication instance
     */
    public function register(Application $app)
    {
        $this->app = $app;

        $app['form.secret'] = md5(__DIR__);

        $app['form.factory'] = $app->share(function () use ($app) {
            $extensions = array(
                new CoreExtension(),
                new CsrfExtension($app['form.csrf_provider']),
            );

            if (isset($app['validator'])) {
                $extensions[] = new FormValidatorExtension($app['validator']);
            }

            return new FormFactory($extensions);
        });

        $app['form.csrf_provider'] = $app->share(function () use ($app) {
            if (isset($app['session'])) {
                return new SessionCsrfProvider($app['session'], $app['form.secret']);
            }

            return new DefaultCsrfProvider($app['form.secret']);
        });

        if (isset($app['form.class_path'])) {
            $app['autoloader']->registerNamespace('Symfony\\Component\\Form', $app['form.class_path']);
        }
    }
}