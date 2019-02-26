<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Console\Client;

use Illuminate\Contracts\Container\Container;

/**
 * Creates instances of Client
 */
class ClientFactory
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return Client
     */
    public function create(): Client
    {
        return $this->container->make(Client::class);
    }
}
