<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Console\Command\Context;

use Magento\Console\Command\Context;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetList extends Context
{
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contextList = $this->contextList->read();
        $current = $this->contextList->getCurrent();
        $strings = array_map(function ($key, $value) use ($current) {
            return ($current === $key ? '* ' : '  ') . str_pad($key, 15) . $value;
        }, array_keys($contextList), array_map(function ($item) { return $item->url; }, $contextList));
        $output->write(join("\n", $strings) . "\n");
    }
}
