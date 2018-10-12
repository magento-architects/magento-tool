<?php
namespace Magento\Console\Command;

use Symfony\Component\Console\Command\Command;
use Magento\Console\ContextList;

class Context extends Command
{
    /**
     * @var ContextList
     */
    protected $contextList;

    /**
     * ContextCommand constructor.
     * @param ContextList $contextList
     */
    public function __construct(ContextList $contextList)
    {
        $this->contextList = $contextList;
        parent::__construct();
    }
}