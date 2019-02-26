<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Console\Command;

use Magento\Console\Client\Client;
use Magento\Console\Client\ClientFactory;
use Magento\Console\Context\ContextList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Generic remote command
 */
class Remote extends Command
{
    /**
     * @var ClientFactory
     */
    private $clientFactory;

    /**
     * @var ContextList
     */
    private $contextList;

    /**
     * @param ClientFactory $clientFactory
     * @param ContextList $contextList
     */
    public function __construct(ClientFactory $clientFactory, ContextList $contextList)
    {
        $this->clientFactory = $clientFactory;
        $this->contextList = $contextList;

        parent::__construct();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = $this->clientFactory->create();

        $response = $client->post(
            $this->contextList->getCurrent()->get('url'),
            $this->contextList->getCurrent()->get('key'),
            Client::TYPE_RUN,
            [
                'name' => $this->getName(),
                'arguments' => $input->getArguments(),
                'options' => $input->getOptions()
            ]
        );

        $tpl = $response->getStatusCode() === 200
            ? '%s'
            : '<error>%s</error>';

        $output->write(sprintf($tpl, $response->getBody()->getContents()));
    }
}
