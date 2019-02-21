<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Console\Command;

use GuzzleHttp\Client;
use Magento\Console\Auth\Generator;
use Magento\Console\ContextList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Remote extends Command
{
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
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $context = $this->contextList->getCurrent();
        $url = $context['url'] . '/remote';
        $client = new Client();

        $publicKey = $context->get('public_key');
        $privateKey = $context->get('private_key');

        $params = [
            'name' => $this->getName(),
            'arguments' => $input->getArguments(),
            'options' => $input->getOptions(),
            'public_key' => $publicKey,
            'type' => 'run'
        ];

        $sign = $this->generator->generate($publicKey, $privateKey, $params);

        $response = $client->post($url, [
            'form_params' => $params + ['sign' => $sign]
        ]);

        $tpl = $response->getStatusCode() === 200
            ? '<info>%s</info>'
            : '<error>%s</error>';

        $output->writeln(sprintf($tpl, $response->getBody()->getContents()));
    }
}
