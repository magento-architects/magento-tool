<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Adds context (magento instance) to the list of available contexts.
 */
namespace Magento\Console\Command\Context;

use Magento\Console\Context\ContextList;
use Magento\Console\Shell\ShellFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Add new context
 */
class AddCommand extends Command
{
    private const NAME = 'context:add';
    private const ARG_NAME = 'name';
    private const ARG_TYPE = 'type';
    private const ARG_URL = 'url';

    /**
     * @var ContextList
     */
    private $contextList;

    /**
     * @var ShellFactory
     */
    private $sshFactory;

    /**
     * @param ContextList $contextList
     * @param ShellFactory $shellFactory
     */
    public function __construct(ContextList $contextList, ShellFactory $shellFactory)
    {
        $this->contextList = $contextList;
        $this->sshFactory = $shellFactory;

        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this->setName(self::NAME)
            ->setDescription('Add context')
            ->addArgument(self::ARG_NAME, InputArgument::REQUIRED, 'Name of context')
            ->addArgument(
                self::ARG_TYPE,
                InputArgument::REQUIRED,
                'Type one of ' . implode(', ', [ShellFactory::TYPE_LOCAL, ShellFactory::TYPE_REMOTE]))
            ->addArgument(self::ARG_URL, InputArgument::REQUIRED, 'URL address');

        parent::configure();
    }

    /**
     * @inheritdoc
     *
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument(self::ARG_NAME);

        $process = $this->sshFactory->create(
            $input->getArgument(self::ARG_TYPE),
            $input->getArgument(self::ARG_URL),
            'list --format=json'
        );
        $process->mustRun();

        $this->contextList->add(
            $name,
            $input->getArgument(self::ARG_TYPE),
            $input->getArgument(self::ARG_URL),
            json_decode($process->getOutput(), true)['commands']
        );

        $output->writeln('<info>Context added.</info>');

        if (!$this->contextList->getCurrentName()) {
            $this->getApplication()
                ->find(SetCommand::NAME)
                ->run(new ArrayInput([SetCommand::ARG_NAME => $name]), $output);
        }
    }
}
