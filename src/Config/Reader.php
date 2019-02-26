<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Console\Config;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

/**
 * Read the application config
 */
class Reader
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @return array
     */
    public function read(): array
    {
        $configFile = HOME_DIR . '/config.yaml';

        if ($this->filesystem->exists($configFile)) {
            return Yaml::parseFile($configFile);
        }

        return [];
    }
}
