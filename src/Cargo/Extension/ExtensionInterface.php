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

/**
 * ExtensionInterface.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
interface ExtensionInterface
{
    /**
     * Registers an extension.
     *
     * @param Application $app An Application instance
     */
    function register(Application $app);
}