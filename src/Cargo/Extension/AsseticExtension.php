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

use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;
use Assetic\Asset\GlobAsset;

use Assetic\Factory\AssetFactory;

/**
 * AsseticExtension.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class AsseticExtension implements ExtensionInterface
{
    /**
     * Registers an extension.
     *
     * @param Application $app An appllication instance
     */
    public function register(Application $app)
    {
    }
}