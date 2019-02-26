<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Remove a context
 */
namespace Magento\Console\Command\Context;

use Magento\Console\Context\ContextList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Remove context
 */
class RemoveCommand extends Command
{
    private const ARG_NAME = 'name';

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
        $this->setName('context:remove')
            ->setDescription('Remove context')
            ->addArgument(self::ARG_NAME, InputArgument::REQUIRED);

        parent::configure();
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument(self::ARG_NAME);

        if ($this->contextList->has($name)) {
            $this->contextList->remove($name);

            $output->writeln('<info>Context removed</info>');

            return;
        }

        $output->writeln('<error>No such context</error>');
    }
}
