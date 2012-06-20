<?php

/*
 * This file is part of the cargo framework
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cargo\Cache;

use Symfony\Component\Config\ConfigCache;
use Cargo\Template\Theme;

/**
 * CacheHandlerInterface.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
interface CacheHandlerInterface
{
  /**
   * Some function on non fresh cache.
   *
   * @param Theme       $theme The theme object
   * @param ConfigCache $cache The cache param
   */
  function onNonFresh(Theme $theme, ConfigCache $cache);

  /**
   * Some functionality on fresh cache.
   *
   * @param Theme       $theme The theme object
   * @param ConfigCache $cache The cache param
   */
  function onFresh(Theme $theme, ConfigCache $cache);
}
