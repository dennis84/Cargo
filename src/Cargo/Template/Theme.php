<?php

namespace Cargo\Template;

class Theme
{
  protected $name;
  protected $dir;
  protected $templates;

  public function __construct($name, $dir, array $templates = array())
  {
    if (!is_dir($dir)) {
      throw new \InvalidArgumentException(sprintf('The directory "%s" does not exist.', $dir));
    }

    $this->name      = $name;
    $this->dir       = $dir;
    $this->templates = $templates;
  }

  public function getName()
  {
    return $this->name;
  }

  public function getDir()
  {
    return $this->dir;
  }

  public function getTemplates()
  {
    return $this->templates;
  }

  public function setTemplates(array $templates)
  {
    $this->templates = $templates;
  }
}
