<?php

/*
 * This file is part of the cargo framework
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cargo\Template;

/**
 * TemplateFile.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class TemplateFile extends \SplFileInfo
{
    /**
     * Constructor.
     *
     * @param string $path The template path
     */
    public function __construct($path)
    {
        if (!is_file($path)) {
            throw new \InvalidArgumentException(sprintf('The file "%s" does not exists.', $path));
        }

        parent::__construct($path);
    }

    /**
     * Reads the template file.
     *
     * @return string
     */
    public function read()
    {
        return $this->content = file_get_contents($this->getRealPath());
    }

    /**
     * Writes the template file.
     *
     * @param string $content The template content
     */
    public function write($content)
    {
        file_put_contents($this->getRealPath(), $content);
        $this->content = $this->read();
    }

    /**
     * Reads the template doc comments.
     *
     * @return string
     */
    public function readDocComments()
    {
        preg_match('/\{\#(.*)\#\}/is', $this->read(), $config);

        if (empty($config[1])) {
            throw new \Exception(sprintf('
                the template-docComments are not valid in "%s". 
                You have to write your annotation like /** @Cargo\Annotation\Template() */',
                $this->path
            ));
        }

        return $config[1];
    }
}
