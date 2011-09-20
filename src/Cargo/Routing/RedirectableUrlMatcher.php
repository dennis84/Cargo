<?php

/*
 * This file is part of the cargo framework
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cargo\Routing;

use Symfony\Component\Routing\Matcher\RedirectableUrlMatcher as BaseRedirectableUrlMatcher;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Matcher\RedirectableUrlMatcherInterface;

/**
 * RedirectableUrlMatcher.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class RedirectableUrlMatcher extends BaseRedirectableUrlMatcher
{
    /**
     * Redirects.
     *
     * @param string $path   The path
     * @param string $route  The route
     * @param string $scheme The scheme
     *
     * @return array
     */
    public function redirect($path, $route, $scheme = null)
    {
        return array(
            '_controller' => function ($url) { return new RedirectResponse($url, 301); },
            'url' => $this->context->getBaseUrl().$path,
        );
    }
}
