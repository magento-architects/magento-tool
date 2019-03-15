<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Console\Command;

use Magento\Console\Context\ContextList;
use Magento\Console\Shell\ShellFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Generic remote command
 */
class Remote extends Command
{
    /**
     * @var ContextList
     */
    private $contextList;

    /**
     * @var ShellFactory
     */
    private $shellFactory;

    /**
     * @param ContextList $contextList
     * @param ShellFactory $shellFactory
     */
    public function __construct(ContextList $contextList, ShellFactory $shellFactory)
    {
        $this->contextList = $contextList;
        $this->shellFactory = $shellFactory;

        parent::__construct();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $process = $this->shellFactory->create(
            $this->contextList->getCurrent()->get('type'),
            $this->contextList->getCurrent()->get('url'),
            (string)$input
        );

        $process->mustRun(function ($type, string $buffer) use ($output) {
            $output->write($buffer);
        });
    }
}
