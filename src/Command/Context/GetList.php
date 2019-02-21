<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Retrieve the list of managed instances (contexts)
 */
namespace Magento\Console\Command\Context;

use Magento\Console\ContextList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetList extends Command
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
        $this->setName('context:list');

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
                $data->get('public_key'),
                $data->get('private_key')
            ];
        }

        if (!$rows) {
            $output->writeln('<error>No defined contexts.</error>');

            return;
        }

        $table = new Table($output);
        $table->setHeaders(['Name', 'URL', 'Public key', 'Private key'])
            ->setRows($rows)
            ->render();
    }
}
