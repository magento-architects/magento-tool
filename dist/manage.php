<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

try {
    require __DIR__ . '/../../app/bootstrap.php';
} catch (\Exception $e) {
    echo 'Autoload error: ' . $e->getMessage();
    exit(1);
}
ini_set('error_reporting', -1);
ini_set('display_errors', 'on');
try {
    $handler = new \Magento\Framework\App\ErrorHandler();
    set_error_handler([$handler, 'handler']);
    $application = new Magento\Framework\Console\Cli('Magento CLI');
    if (isset($_GET['config'])) {
        $output = new \Symfony\Component\Console\Output\BufferedOutput();
        $commands = array_map(function ($item) {
            $arguments = array_map(function ($argument) {
                return [
                    'name' => $argument->getName(),
                    'description' => $argument->getDescription(),
                    'is_array' => $argument->isArray(),
                    'is_requied' => $argument->isRequired(),
                    'default' => $argument->getDefault()
                ];
            },
                $item->getDefinition()->getArguments()
            );
            $options = array_map(function ($argument) {
                return [
                    'name' => $argument->getName(),
                    'description' => $argument->getDescription(),
                    'shortcut' => $argument->getShortcut(),
                    'default' => $argument->getDefault()
                ];
            },
                $item->getDefinition()->getOptions()
            );

            return [
                'name' => $item->getName(),
                'description' => $item->getDescription(),
                'help' => $item->getHelp(),
                'usages' => $item->getUsages(),
                'definition' => [
                    'options' => $options,
                    'arguments' => $arguments
                ]
            ];
        }, $application->getApplicationCommands());
        echo json_encode($commands);
    } else {
        $inputArgs = json_decode($_POST);
        $input = new \Symfony\Component\Console\Input\ArrayInput($inputArgs['arguments']);
        array_walk($inputArgs['optinos'], function ($value, $key) use ($input) {
            $input->setOption($key, $value);
        });
        $output = new \Symfony\Component\Console\Output\StreamOutput(fopen('php://output', 'w'));
        $application->setAutoExit(false);
        $application->run($input, $output);
    }
} catch (\Exception $e) {
    while ($e) {
        echo $e->getMessage();
        echo $e->getTraceAsString();
        echo "\n\n";
        $e = $e->getPrevious();
    }
    exit(Magento\Framework\Console\Cli::RETURN_FAILURE);
}
