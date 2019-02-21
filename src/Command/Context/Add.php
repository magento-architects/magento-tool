<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Adds context (magento instance) to the list of available contexts.
 */
namespace Magento\Console\Command\Context;

use GuzzleHttp\Client;
use Magento\Console\Auth\Generator;
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
    private const ARG_PUBLIC_KEY = 'public-key';
    private const ARG_PRIVATE_KEY = 'private-key';

    /**
     * @var ContextList
     */
    private $contextList;

    /**
     * @var Generator
     */
    private $generator;

    /**
     * @param ContextList $contextList
     * @param Generator $generator
     */
    public function __construct(ContextList $contextList, Generator $generator)
    {
        $this->contextList = $contextList;
        $this->generator = $generator;

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
            ->addArgument(self::ARG_PUBLIC_KEY, InputArgument::REQUIRED)
            ->addArgument(self::ARG_PRIVATE_KEY, InputArgument::REQUIRED);

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

        $sign = $this->generator->generate(
            $input->getArgument(self::ARG_PUBLIC_KEY),
            $input->getArgument(self::ARG_PRIVATE_KEY),
            []
        );

        $client = new Client();
        $response = $client->post($url . '/manage.php', [
            'form_params' => [
                'public_key' => $input->getArgument(self::ARG_PUBLIC_KEY),
                'sign' => $sign,
                'type' => 'list'
            ]
        ]);

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
            $url,
            $commands,
            $input->getArgument(self::ARG_PUBLIC_KEY),
            $input->getArgument(self::ARG_PRIVATE_KEY)
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
    private function getCommands(string $url, InputInterface $input): array
    {
        $sign = $this->generator->generate(
            $input->getArgument(self::ARG_PUBLIC_KEY),
            $input->getArgument(self::ARG_PRIVATE_KEY),
            []
        );

        $client = new Client();
        $response = $client->post($url . '/manage.php', [
            'form_params' => [
                'public_key' => $input->getArgument(self::ARG_PUBLIC_KEY),
                'sign' => $sign,
                'type' => 'list'
            ]
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException($response->getBody()->getContents());
        }

        return (array)json_decode(
            $response->getBody()->getContents(),
            JSON_OBJECT_AS_ARRAY
        );
    }
}
