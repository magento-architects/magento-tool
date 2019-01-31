<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Display current context - the name of the instance on which all commands will be executed
 */
namespace Magento\Console\Command\Context;

use Magento\Console\ContextList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Get extends Command
{
    /**
     * @var ContextList
     */
    private $contextList;

    /**
     * @param ContextList $contextList
     */
    public function __construct(ContextList $contextList)
    {
        $this->contextList = $contextList;

        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this->setName('context');

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($context = $this->contextList->getCurrentName()) {
            $output->writeln($context);

            return;
        }

        $output->writeln('<error>No context set.</error>');
    }
}
