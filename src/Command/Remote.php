<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Console\Command;

use GuzzleHttp\Client;
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
     * @param ContextList $contextList
     */
    public function __construct(ContextList $contextList)
    {
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
        $context = $this->contextList->getCurrent();
        $url = $context['url'] . '/manage.php';
        $client = new Client();

        $response = $client->post($url, [
            'form_params' => [
                'arguments' => $input->getArguments(),
                'options' => [] //$input->getOptions()
            ]
        ]);

        $tpl = $response->getStatusCode() === 200
            ? '<info>%s</info>'
            : '<error>%s</error>';

        $output->writeln(sprintf($tpl, $response->getBody()->getContents()));
    }
}
