<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
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
