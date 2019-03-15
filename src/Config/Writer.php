<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Console\Config;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

/**
 * Write the application config
 */
class Writer
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
     * @param array $data
     * @return int
     */
    public function write(array $data): int
    {
        $configFile = HOME_DIR . '/config.yaml';

        return $this->filesystem->put(
            $configFile,
            Yaml::dump($data, 4, 2)
        );
    }
}
