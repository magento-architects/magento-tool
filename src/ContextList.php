<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 *
 * List of managed contexts (instances)
 */
namespace Magento\Console;

class ContextList
{
    /**
     * @var string
     */
    private $listPersistenceFile;

    /**
     * @var string
     */
    private $currentContextFile;

    /**
     * @var string
     */
    private $current = null;

    /**
     * @var [string]
     */
    private $list = null;

    /**
     * @param $homeDir
     */
    public function __construct($homeDir)
    {
        $this->listPersistenceFile = $homeDir . DIRECTORY_SEPARATOR . 'contexts';
        $this->currentContextFile = $homeDir . DIRECTORY_SEPARATOR . 'context';
    }

    /**
     * Read list of contexts available to work with
     *
     * @return array
     */
    public function read() : array
    {
        if ($this->list !== null) {
            return $this->list;
        }
        $contexts = [];
        if (is_readable($this->listPersistenceFile)) {
            $contexts = json_decode(file_get_contents($this->listPersistenceFile));
        }
        $this->list = (array) $contexts;
        return $this->list;
    }

    /**
     * Persist available context list
     *
     * @param [string] $contexts
     * @return bool
     */
    public function write(array $contexts) : bool
    {
        $this->list = $contexts;
        return !!file_put_contents($this->listPersistenceFile, json_encode($contexts));
    }

    /**
     * @return string
     */
    public function getCurrent() : ?string
    {
        if ($this->current !== null) {
            return $this->current;
        }
        if (is_readable($this->currentContextFile)) {
            return json_decode(file_get_contents($this->currentContextFile));
        }
        return null;
    }

    /**
     * Set and persist current context. All following commands will be executed in this context
     * @param string $name
     * @return bool|int
     */
    public function setCurrent(string $name) : bool
    {
        $this->current = $name;
        return !!file_put_contents($this->currentContextFile, json_encode($name));
    }
}