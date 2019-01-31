<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Console\Config;

use Symfony\Component\Yaml\Yaml;

class Reader
{
    /**
     * @return array
     */
    public function read(): array
    {
        $configFile = HOME_DIR . '/config.yaml';

        if (@file_exists($configFile)) {
            return Yaml::parseFile($configFile);
        }

        return [];
    }
}
