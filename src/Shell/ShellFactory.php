<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Console\Shell;

use Symfony\Component\Process\Process;

class ShellFactory
{
    public const TYPE_REMOTE = 'remote';
    public const TYPE_LOCAL = 'local';

    /**
     * @param string $type
     * @param string $url
     * @param string $command
     * @return Process
     */
    public function create(string $type, string $url, string $command): Process
    {
        $command = './bin/magento ' . $command;

        if ($type === self::TYPE_LOCAL) {
            return Process::fromShellCommandline($command, $url);
        }

        if ($type === self::TYPE_REMOTE) {
            return new Process(['ssh', $url, $command]);
        }

        throw new \RuntimeException('No available remote type');
    }
}
