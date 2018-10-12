<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Remote extends Command
{
    /**
     * @var \stdClass
     */
    private $contextData;

    /**
     * @param \stdClass $contextData
     * @param null $name
     */
    public function __construct($contextData, $name = null)
    {
        $this->contextData = $contextData;
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $postData = json_encode(['arguments' => $input->getArguments(), 'options' => $input->getOptions()]);
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->contextData->url . '/manage.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_FOLLOWLOCATION => true
        ]);
        $result = curl_exec($ch);
        $output->writeln($result);
        curl_close($ch);
    }
}
