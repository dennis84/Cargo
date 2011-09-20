<?php

/*
 * This file is part of the cargo framework
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cargo;

use Symfony\Component\EventDispatcher\Event;

/**
 * ApplicationEvents.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class ApplicationEvents
{
    const REQUEST  = 'application.request';
    const RESPONSE = 'application.response';
}