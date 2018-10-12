<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Load command metadata from current context (instance)
 */
namespace Magento\Console\Command\Context;

use Magento\Console\Command\Context;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Load extends Context
{
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contexts = $this->contextList->read();
        $contextData = $contexts[$this->contextList->getCurrent()];
        $meta = json_decode(loadContextMetadata($contextData->url . "/manage.php?config"));
        if (is_null($meta)) {
            throw new \Exception("Could not load context metadata");
        }
        $contexts[$this->contextList->getCurrent()]->commands = $meta;
        $this->contextList->write($contexts);
    }
}