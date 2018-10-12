<?php
namespace Magento\Console;


class ContextList
{
    private $file;

    private $currentFile;

    private $current = null;

    private $list = null;

    /**
     * ContextList constructor.
     * @param $homeDir
     */
    public function __construct($homeDir)
    {
        $this->file = $homeDir . DIRECTORY_SEPARATOR . 'contexts';
        $this->currentFile = $homeDir . DIRECTORY_SEPARATOR . 'context';
    }

    public function read()
    {
        if ($this->list !== null) {
            return $this->list;
        }
        $contexts = [];
        if (is_readable($this->file)) {
            $contexts = json_decode(file_get_contents($this->file));
        }
        $this->list = (array) $contexts;
        return $this->list;
    }

    public function write($contexts)
    {
        $this->list = $contexts;
        return file_put_contents($this->file, json_encode($contexts));
    }

    public function getCurrent()
    {
        if ($this->current !== null) {
            return $this->current;
        }
        if (is_readable($this->currentFile)) {
            return json_decode(file_get_contents($this->currentFile));
        }
        return null;
    }

    public function setCurrent($name)
    {
        $this->current = $name;
        return file_put_contents($this->currentFile, json_encode($name));
    }
}