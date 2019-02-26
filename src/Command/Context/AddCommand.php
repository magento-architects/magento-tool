<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Adds context (magento instance) to the list of available contexts.
 */
namespace Magento\Console\Command\Context;

use Magento\Console\Client\Client;
use Magento\Console\Client\ClientFactory;
use Magento\Console\Context\ContextList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Add new context
 */
class AddCommand extends Command
{
    private const ARG_NAME = 'name';
    private const ARG_URL = 'url';
    private const ARG_KEY = 'key';

    /**
     * @var ContextList
     */
    private $contextList;

    /**
     * @var ClientFactory
     */
    private $clientFactory;

    /**
     * @param ContextList $contextList
     * @param ClientFactory $clientFactory
     */
    public function __construct(ContextList $contextList, ClientFactory $clientFactory)
    {
        $this->contextList = $contextList;
        $this->clientFactory = $clientFactory;

        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this->setName('context:add')
            ->setDescription('Add context')
            ->addArgument(self::ARG_NAME, InputArgument::REQUIRED)
            ->addArgument(self::ARG_URL, InputArgument::REQUIRED)
            ->addArgument(self::ARG_KEY, InputArgument::REQUIRED);

        parent::configure();
    }

    /**
     * {@inheritdoc}
     *
     * @throws GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument(self::ARG_NAME);

        $client = $this->clientFactory->create();
        $response = $client->post(
            $input->getArgument(self::ARG_URL),
            $input->getArgument(self::ARG_KEY),
            Client::TYPE_LIST,
            []
        );

        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException($response->getBody()->getContents());
        }

        $commands = (array)json_decode(
            $response->getBody()->getContents(),
            JSON_OBJECT_AS_ARRAY
        );

        if (!$commands) {
            throw new \RuntimeException('Commands are not defined');
        }

        $this->contextList->add(
            $name,
            $input->getArgument(self::ARG_URL),
            $input->getArgument(self::ARG_KEY),
            $commands
        );

        $output->writeln('<info>Context added.</info>');

        if (!$this->contextList->getCurrentName()) {
            $this->getApplication()
                ->find(SetCommand::NAME)
                ->run(new ArrayInput([SetCommand::ARG_NAME => $name]), $output);
        }
    }
}
