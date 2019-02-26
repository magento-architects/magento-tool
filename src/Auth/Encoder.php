<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Console\Auth;

use Firebase\JWT\JWT;

/**
 * Encode payload data with key
 */
class Encoder
{
    /**
     * @param array $params
     * @param string $key
     * @return string
     */
    public function encode(array $params, string $key): string
    {
        return JWT::encode($params, $key);
    }
}
