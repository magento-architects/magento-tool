<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Console;

use Illuminate\Contracts\Container\Container;
use Magento\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class Application.
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
     * @inheritdoc
     */
    protected function getDefaultCommands(): array
    {
        $stdCommands = [
            $this->container->make(Command\Context\Get::class),
            $this->container->make(Command\Context\Get::class),
            $this->container->make(Command\Context\GetList::class),
            $this->container->make(Command\Context\Add::class),
            $this->container->make(Command\Context\Remove::class),
            $this->container->make(Command\Context\Set::class)
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
     */
    private function fetchMagentoCommands(): array
    {
        $context = $this->contextList->getCurrent();

        if (!$context) {
            return [];
        }

        $commands = [];

        foreach ($context->get('commands') as $cName => $cData) {
            /** @var Command\Remote $command */
            $command = $this->container->make(Command\Remote::class);
            $command->setName($cName)
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
