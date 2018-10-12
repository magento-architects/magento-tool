<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Console\Command\Context;

use Magento\Console\Command\Context;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Remove extends Context
{
    protected function configure()
    {
        $this->addArgument('name', \Symfony\Component\Console\Input\InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contexts = $this->contextList->read();
        unset($contexts[$input->getArgument('name')]);
        $this->contextList->write($contexts);
    }
}