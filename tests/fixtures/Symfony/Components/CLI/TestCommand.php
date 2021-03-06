<?php

use Symfony\Components\CLI\Command\Command;
use Symfony\Components\CLI\Input\InputInterface;
use Symfony\Components\CLI\Output\OutputInterface;

class TestCommand extends Command
{
  protected function configure()
  {
    $this
      ->setName('namespace:name')
      ->setAliases(array('name'))
      ->setDescription('description')
      ->setHelp('help')
    ;
  }

  public function mergeApplicationDefinition()
  {
    return parent::mergeApplicationDefinition();
  }

  public function getApplication()
  {
    return $this->application;
  }

  public function getDefinition()
  {
    return $this->definition;
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $output->write('execute called');
  }

  protected function interact(InputInterface $input, OutputInterface $output)
  {
    $output->write('interact called');
  }
}
