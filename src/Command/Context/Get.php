<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Display current context - the name of the instance on which all commands will be executed
 */
namespace Magento\Console\Command\Context;

use Magento\Console\Command\Context;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Get extends Context
{
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln($this->contextList->getCurrent());
    }
}
