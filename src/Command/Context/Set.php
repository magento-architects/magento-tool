<?php
namespace Magento\Console\Command\Context;

use Magento\Console\Command\Context;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Set extends Context
{
    protected function configure()
    {
        $this->addArgument('name', \Symfony\Component\Console\Input\InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contexts = $this->contextList->read();
        if (!isset($contexts[$input->getArgument('name')])) {
            $output->writeln('Context ' . $input->getArgument('name') . ' does not exist');
        } else {
            $this->contextList->setCurrent($input->getArgument('name'));
        }
    }
}