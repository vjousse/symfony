<?php

namespace Symfony\Components\CLI\Input;

/*
 * This file is part of the symfony framework.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * ArrayInput represents an input provided as an array.
 *
 * Usage:
 *
 *     $input = new ArrayInput(array('name' => 'foo', '--bar' => 'foobar'));
 *
 * @package    symfony
 * @subpackage cli
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 */
class ArrayInput extends Input
{
  protected $parameters;

  /**
   * Constructor.
   *
   * @param array      $param An array of parameters
   * @param Definition $definition A Definition instance
   */
  public function __construct(array $parameters, Definition $definition = null)
  {
    $this->parameters = $parameters;

    parent::__construct($definition);
  }

  /**
   * Returns the first argument from the raw parameters (not parsed).
   *
   * @return string The value of the first argument or null otherwise
   */
  public function getFirstArgument()
  {
    foreach ($this->parameters as $key => $value)
    {
      if ($key && '-' === $key[0])
      {
        continue;
      }

      return $value;
    }
  }

  /**
   * Returns true if the raw parameters (not parsed) contains a value.
   *
   * This method is to be used to introspect the input parameters
   * before it has been validated. It must be used carefully.
   *
   * @param string|array $value The values to look for in the raw parameters (can be an array)
   *
   * @return Boolean true if the value is contained in the raw parameters
   */
  public function hasParameterOption($values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    foreach ($this->parameters as $k => $v)
    {
      if (!is_int($k))
      {
        $v = $k;
      }

      if (in_array($v, $values))
      {
        return true;
      }
    }

    return false;
  }

  /**
   * Processes command line arguments.
   */
  protected function parse()
  {
    foreach ($this->parameters as $key => $value)
    {
      if ('--' === substr($key, 0, 2))
      {
        $this->addLongOption(substr($key, 2), $value);
      }
      elseif ('-' === $key[0])
      {
        $this->addShortOption(substr($key, 1), $value);
      }
      else
      {
        $this->addArgument($key, $value);
      }
    }
  }

  /**
   * Adds a short option value.
   *
   * @param string $shortcut The short option key
   * @param mixed  $value    The value for the option
   */
  protected function addShortOption($shortcut, $value)
  {
    if (!$this->definition->hasShortcut($shortcut))
    {
      throw new \RuntimeException(sprintf('The "-%s" option does not exist.', $shortcut));
    }

    $this->addLongOption($this->definition->getOptionForShortcut($shortcut)->getName(), $value);
  }

  /**
   * Adds a long option value.
   *
   * @param string $name  The long option key
   * @param mixed  $value The value for the option
   */
  protected function addLongOption($name, $value)
  {
    if (!$this->definition->hasOption($name))
    {
      throw new \RuntimeException(sprintf('The "--%s" option does not exist.', $name));
    }

    $option = $this->definition->getOption($name);

    if (null === $value)
    {
      if ($option->isParameterRequired())
      {
        throw new \RuntimeException(sprintf('The "--%s" option requires a value.', $name));
      }

      $value = $option->isParameterOptional() ? $option->getDefault() : true;
    }

    $this->options[$name] = $value;
  }

  /**
   * Adds an argument value.
   *
   * @param string $name  The argument name
   * @param mixed  $value The value for the argument
   */
  protected function addArgument($name, $value)
  {
    if (!$this->definition->hasArgument($name))
    {
      throw new \RuntimeException(sprintf('The "%s" argument does not exist.', $name));
    }

    $this->arguments[$name] = $value;
  }
}
