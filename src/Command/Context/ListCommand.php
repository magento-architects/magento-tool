<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Console\Command\Context;

use Magento\Console\Context\ContextList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Display list of contexts
 */
class ListCommand extends Command
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
        $this->setName('context:list')
            ->setDescription('Display all contexts');

        parent::configure();
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $rows = [];

        foreach ($this->contextList->getAll() as $name => $data) {
            $rows[] = [
                $name,
                $data->get('url'),
                $data->get('key')
            ];
        }

        if (!$rows) {
            $output->writeln('<error>No available contexts.</error>');

            return;
        }

        $table = new Table($output);
        $table->setHeaders(['Name', 'URL', 'Key'])
            ->setRows($rows)
            ->render();
    }
}
