<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Console\Client;

use Magento\Console\Auth\Encoder;
use Psr\Http\Message\ResponseInterface;

/**
 * @inheritdoc
 */
class Client extends \GuzzleHttp\Client
{
    public const TYPE_RUN = 'run';
    public const TYPE_LIST = 'list';

    /**
     * @var Encoder
     */
    private $encoder;

    /**
     * @param Encoder $encoder
     */
    public function __construct(Encoder $encoder)
    {
        $this->encoder = $encoder;

        parent::__construct([]);
    }

    /**
     * @param string $uri
     * @param string $key
     * @param string $type
     * @param array $params
     * @return ResponseInterface
     */
    public function post(
        string $uri,
        string $key,
        string $type,
        array $params
    ): ResponseInterface {
        $params['type'] = $type;

        return parent::post($uri, [
            'form_params' => [
                'token' => $this->encoder->encode($params, $key)
            ]
        ]);
    }
}
