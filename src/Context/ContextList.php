<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 *
 * List of managed contexts (instances)
 */
namespace Magento\Console\Context;

use Illuminate\Config\Repository;
use Magento\Console\Config\Reader;
use Magento\Console\Config\Writer;

/**
 * Context repository.
 */
class ContextList
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var Writer
     */
    private $writer;

    /**
     * @param Reader $reader
     * @param Writer $writer
     */
    public function __construct(Reader $reader, Writer $writer)
    {
        $this->reader = $reader;
        $this->writer = $writer;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        $config = $this->reader->read();

        return isset($config['contexts'][$name]);
    }

    /**
     * @param string $name
     * @param string $type
     * @param string $url
     * @param array $commands
     */
    public function add(string $name, string $type, string $url, array $commands): void
    {
        $config = $this->reader->read();
        $config['contexts'][$name] = [
            'name' => $name,
            'type' => $type,
            'url' => $url,
            'commands' => $commands,
        ];

        $this->writer->write($config);
    }

    /**
     * @param string $name
     */
    public function remove(string $name): void
    {
        $config = $this->reader->read();

        unset($config['contexts'][$name]);

        if (isset($config['current']) && $config['current'] === $name) {
            unset($config['current']);
        }

        $this->writer->write($config);
    }

    /**
     * @return Repository
     */
    public function getCurrent(): Repository
    {
        $config = $this->reader->read();
        $current = $this->getCurrentName();

        if (!$current) {
            throw new \RuntimeException('Current context not set');
        }

        return new Repository($config['contexts'][$current]);
    }

    /**
     * @return bool
     */
    public function hasCurrent(): bool
    {
        return $this->getCurrentName() !== null;
    }

    /**
     * @return null|string
     */
    public function getCurrentName(): ?string
    {
        $config = $this->reader->read();

        return $config['current'] ?? null;
    }

    /**
     * @return Repository[]
     */
    public function getAll(): array
    {
        $config = $this->reader->read();

        if (!array_key_exists('contexts', $config)) {
            return [];
        }

        $contexts = [];

        foreach ($config['contexts'] as $name => $context) {
            $contexts[$name] = new Repository($context);
        }

        return $contexts;
    }

    /**
     * Set and persist current context.
     * All following commands will be executed in this context
     *
     * @param string $name
     * @return bool|int
     */
    public function setCurrent(string $name): bool
    {
        $config = $this->reader->read();
        $config['current'] = $name;

        return $this->writer->write($config);
    }
}
