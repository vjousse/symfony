<?php

namespace Symfony\Components\CLI\Output;

/*
 * This file is part of the symfony framework.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * NullOutput suppresses all output.
 *
 *     $output = new NullOutput();
 *
 * @package    symfony
 * @subpackage cli
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 */
class NullOutput extends Output
{
  /**
   * Writes a message to the output.
   *
   * @param string $message A message to write to the output
   */
  public function doWrite($message)
  {
  }
}
