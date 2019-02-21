<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Console\Auth;

class Generator
{
    /**
     * @param string $publicKey
     * @param string $privateKey
     * @param array $params
     * @return string
     */
    public function generate(string $publicKey, string $privateKey, array $params): string
    {
        unset(
            $params['public_key'],
            $params['type']
        );

        ksort($params);

        return sha1($this->toString($params) . $publicKey . $privateKey);
    }

    /**
     * @param array $params
     * @param string $string
     * @return string
     */
    private function toString(array $params, $string = ''): string
    {
        foreach ($params as $key => $value) {
            if (is_array($value) && $value) {
                $string .= $this->toString($value, $string);
            } elseif (is_string($value) && $value) {
                $string .= $key . '=' . $value;
            }
        }

        return $string;
    }
}
