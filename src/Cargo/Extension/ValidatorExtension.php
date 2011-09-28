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

use Symfony\Component\Validator\Validator;
use Symfony\Component\Validator\Mapping\ClassMetadataFactory;
use Symfony\Component\Validator\Mapping\Loader\StaticMethodLoader;
use Symfony\Component\Validator\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

/**
 * ValidatorExtension.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class ValidatorExtension implements ExtensionInterface
{
    /**
     * Registers an extension.
     *
     * @param Application $app An Application instance
     */
    public function register(Application $app)
    {
        $app['validator'] = $app->share(function () use ($app) {
            return new Validator(
                $app['validator.mapping.class_metadata_factory'],
                $app['validator.validator_factory']
            );
        });

        $app['validator.mapping.class_metadata_factory'] = $app->share(function () use ($app) {
            return new ClassMetadataFactory(
                new AnnotationLoader(new AnnotationReader())
            );
        });

        $app['validator.validator_factory'] = $app->share(function () {
            return new ConstraintValidatorFactory();
        });

        if (isset($app['validator.class_path'])) {
            $app['autoloader']->registerNamespace(
                'Symfony\\Component\\Validator',
                $app['validator.class_path']
            );
        }

        AnnotationRegistry::registerAutoloadNamespace(
            'Symfony\\Component\\Validator\\Constraint',
            $app['validator.class_path']
        );
    }
}