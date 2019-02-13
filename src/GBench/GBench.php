<?php
namespace GBench;

/**
 * Gbench
 */
class GBench
{
    use Traits\Converter;

    /** @var array <\GBench\Record> record list */
    private $record_list = [];
    /** @var array <int> started at list */
    private $started_at = [];
    /** @var \GBench\AbstractHandler Abstract Handler */
    private $handler = null;

    /**
     * construct
     * @param \GBench\AbstractHandler $handler
     */
    public function __construct(\GBench\AbstractHandler $handler = null)
    {
        $this->handler = $handler;
        $this->reset();
    }

    /**
     * reset All Record
     */
    public function reset(): void
    {
        $this->started_at = [];
        $this->record_list = [];
    }

    /**
     * GBench start
     * @return self
     */
    public function start(): self
    {
        $this->started_at[] = $this->getNow();

        if ($this->handler) {
            $this->handler->startAfter($this);
        }

        return $this;
    }

    /**
     * GBench stop and Create Record with Label
     * @param string|null $label
     * @return self
     */
    public function stop(?string $label = null): self
    {
        if (!count($this->started_at)) {
            throw new \LogicException('Can be called after the start() call.');
        }

        $record = new \GBench\Record(
            $label ?: 'default',
            array_pop($this->started_at),
            $this->getNow(),
            $this->record_list ? end($this->record_list)->getAccumulateMemory() : 0
        );

        $this->record_list[] = $record;

        if ($this->handler) {
            $this->handler->stopAfter($this, $record);
        }

        return $this;
    }

    /**
     * Intermediate Record generation with Label
     * @param string|null $label
     * @return self
     */
    public function record(?string $label = null): self
    {
        return $this->stop($label)->start();
    }

    /**
     * wrap a callable with GBench
     * @param callable $callable
     * @param string|null $label
     * @return mixed
     */
    public function run(callable $callable, ?string $label = null)
    {
        if (!is_callable($callable)) {
            throw new \LogicException('This $callable is not callable');
        }

        $this->start($label);

        $result = $callable();

        $this->stop($label);

        return $result;
    }

    /**
     * get Records
     * @return array <\GBench\Record>
     */
    public function getRecords(): array
    {
        return $this->record_list;
    }

    /**
     * get sum of Records Duration milliseconds
     * @return int
     */
    public function getDuration(): int
    {
        $milliseconds = 0;

        foreach ($this->record_list as $record) {
            $milliseconds += $record->getDuration();
        }

        return $milliseconds;
    }

    /**
     * get sum of Records total usage memory
     * @return int
     */
    public function getMemory(): int
    {
        $usage_memory = 0;

        if ($this->record_list) {
            $usage_memory = end($this->record_list)->getAccumulateMemory();
        }

        return $usage_memory;
    }

    /**
     * get now milliseconds
     * @return int
     */
    private function getNow(): int
    {
        return microtime(true) * 1000;
    }

    public function __toString()
    {
        return sprintf(
            '%s - %d ms',
            self::readableSize($this->getMemory()),
            $this->getDuration()
        );
    }
}
