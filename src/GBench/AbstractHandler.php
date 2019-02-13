<?php
namespace GBench;

/**
 * Abstract Handler
 */
abstract class AbstractHandler
{
    /**
     * Handler after start
     * @param \GBench\GBench $gbench
     */
    abstract public function startAfter(\GBench\GBench $gbench);

    /**
     * Handler after stop
     * @param \GBench\GBench $gbench
     * @param \GBench\Record $record
     */
    abstract public function stopAfter(\GBench\GBench $gbench, \GBench\Record $record);
}
