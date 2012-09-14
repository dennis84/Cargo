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

use Doctrine\Common\Annotations\Annotation;

/**
 * RequestAssert.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 *
 * @Annotation
 */
class RequestAssert
{
    /**
     * @var array
     */
    protected $assertions;

    /**
     * Constructor.
     *
     * @param array $data The annotation data
     */
    public function __construct($data)
    {
        if (!isset($data['value'])) {
            throw new \InvalidArgumentException('You must define assertions for this annotation.');
        }

        foreach ($data['value'] as $key => $value) {
            $this->assertions[$key] = $value;
        }
    }

    /**
     * Gets the request assertions.
     *
     * @return array
     */
    public function getAssertions()
    {
        return $this->assertions;
    }
}
