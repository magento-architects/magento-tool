<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Adds context (magento instance) to the list of available contexts.
 */
namespace Magento\Console\Command\Context;

use GuzzleHttp\Client;
use Magento\Console\ContextList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use GuzzleHttp\Exception\GuzzleException;

class Add extends Command
{
    private const ARG_NAME = 'name';
    private const ARG_URL = 'url';

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
        $this->setName('context:add')
            ->setDescription('Add context')
            ->addArgument(self::ARG_NAME, InputArgument::REQUIRED)
            ->addArgument(self::ARG_URL, InputArgument::REQUIRED);

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
        $url = $input->getArgument(self::ARG_URL);
        $commands = $this->getCommands($url);

        if (!$commands) {
            $output->writeln('<error>Application endpoint is not correct.</error>');

            return;
        }

        $this->contextList->add(
            $name,
            $url,
            $commands
        );

        $output->writeln('<info>Context added.</info>');

        if (!$this->contextList->getCurrentName()) {
            $this->contextList->setCurrent($name);

            $output->writeln(sprintf('<info>Context changed to %s.</info>', $name));
        }
    }

    /**
     * @param string $url
     * @return array
     * @throws GuzzleException
     */
    private function getCommands(string $url): array
    {
        $url .= '/manage.php?config';

        $client = new Client();
        $response = $client->request('GET', $url);

        if ($response->getStatusCode() !== 200) {
            return [];
        }

        return (array)json_decode(
            $response->getBody()->getContents(),
            JSON_OBJECT_AS_ARRAY
        );
    }
}
