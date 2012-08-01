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
     * Gets the file header with doc comments. This method reads the file until
     * the "#}" characters then stops.
     *
     * @return string
     */
    public function readHeader()
    {
        $file = new \SplFileObject($this->getRealPath(), 'r');
        $rec  = true;
        $doc  = '';

        while ($rec && !$file->eof()) {
            $doc .= $file->current();
            if (false !== strpos($file->current(), '#}')) {
                $rec = false;
            }
            $file->next();
        }

        return $doc;
    }

    /**
     * Reads the template doc comments.
     *
     * @return string
     */
    public function readDocComments()
    {
        preg_match('#\{\#(.*)\#\}#su', $this->readHeader(), $config);

        if (empty($config[1])) {
            throw new \Exception(
                sprintf('the template doc comments are not valid in "%s".', $this->getRealPath())
            );
        }

        return $config[1];
    }
}
