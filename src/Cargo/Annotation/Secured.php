<?php

/*
 * This file is part of the cargo framework
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cargo\Annotation;

/**
 * Secured.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 *
 * @Annotation
 */
class Secured
{
    /**
     * @var string
     */
    protected $role;

    /**
     * Constructor.
     *
     * @param array $data The annotation data
     */
    public function __construct($data)
    {
        $this->role = $data['value'];
    }

    /**
     * Gets the role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }
}
