<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Console;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container;
use Magento\Console\Command;
use Magento\Console\Context\ContextList;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * General application
 */
class Application extends \Symfony\Component\Console\Application
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var ContextList
     */
    private $contextList;

    /**
     * @param Container $container
     * @param ContextList $contextList
     */
    public function __construct(Container $container, ContextList $contextList)
    {
        $this->container = $container;
        $this->contextList = $contextList;

        parent::__construct();
    }

    /**
     * @return array
     * @throws BindingResolutionException
     */
    protected function getDefaultCommands(): array
    {
        $stdCommands = [
            $this->container->make(Command\Context\GetCommand::class),
            $this->container->make(Command\Context\GetCommand::class),
            $this->container->make(Command\Context\ListCommand::class),
            $this->container->make(Command\Context\AddCommand::class),
            $this->container->make(Command\Context\RemoveCommand::class),
            $this->container->make(Command\Context\SetCommand::class)
        ];
        $magentoCommands = $this->fetchMagentoCommands();

        return array_merge(
            parent::getDefaultCommands(),
            $stdCommands,
            $magentoCommands
        );
    }

    /**
     * @return array
     * @throws BindingResolutionException
     */
    private function fetchMagentoCommands(): array
    {
        if (!$this->contextList->getCurrentName()) {
            return [];
        }

        $context = $this->contextList->getCurrent();
        $commands = [];

        foreach ($context->get('commands') as $cData) {
            if (in_array($cData['name'], ['list', 'help'])) {
                continue;
            }

            /** @var Command\Remote $command */
            $command = $this->container->make(Command\Remote::class);
            $command->setName($cData['name'])
                ->setDescription($cData['description'])
                ->setHelp($cData['help']);

            foreach ($cData['definition']['arguments'] as $aName => $aData) {
                $command->addArgument(
                    $aName,
                    $aData['mode'] ?? InputArgument::OPTIONAL,
                    $aData['description'] ?? ''
                );
            }

            foreach ($cData['definition']['options'] as $oName => $oData) {
                if (in_array($oName, ['help', 'quiet', 'verbose', 'version', 'ansi', 'no-ansi', 'no-interaction'])) {
                    continue;
                }

                $command->addOption(
                    $oName,
                    $oData['shortcut'] ?? null,
                    $oData['mode'] ?? InputOption::VALUE_OPTIONAL,
                    $oData['description'] ?? ''
                );
            }

            $commands[] = $command;
        }

        return $commands;
    }
}
