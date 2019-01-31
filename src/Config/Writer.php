<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Console\Config;

use Symfony\Component\Yaml\Yaml;

class Writer
{
    public function write(array $data)
    {
        $configFile = HOME_DIR . '/config.yaml';

        return file_put_contents($configFile, Yaml::dump($data, 4, 2));
    }
}
