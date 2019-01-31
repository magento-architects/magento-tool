<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Set current context - all following commands will be executed on this context (instance)
 */
namespace Magento\Console\Command\Context;

use Magento\Console\ContextList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Set extends Command
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
        $this->setName('context:set')
            ->addArgument('name', InputArgument::REQUIRED);
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');

        if (!$this->contextList->has($name)) {
            $output->writeln(sprintf('<error>Context "%s" does not exists</error>', $name));

            return;
        }

        $this->contextList->setCurrent($input->getArgument('name'));

        $output->writeln('<info>Context loaded</info>');
    }
}
