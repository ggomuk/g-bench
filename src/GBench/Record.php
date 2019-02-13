<?php
namespace GBench;

/**
 * Record
 */
class Record
{
    use Traits\Converter;

    /** @var string record label */
    private $label;
    /** @var int start at */
    private $started_at;
    /** @var int end at */
    private $ended_at;
    /** @var int this record usage memory size */
    private $usage_memory;
    /** @var int previous cumulative memory size */
    private $accumulated_usage_memory;

    /**
     * construct
     * @param string $label
     * @param int $started_at
     * @param int $ended_at
     * @param int $accumulated_usage_memory
     */
    public function __construct(
        string $label,
        int $started_at,
        int $ended_at,
        int $accumulated_usage_memory = 0
    ) {
        $this->label = $label;
        $this->started_at = $started_at;
        $this->end_at = $ended_at;
        $this->accumulated_usage_memory = $accumulated_usage_memory;
        $this->usage_memory = memory_get_usage(true) - $accumulated_usage_memory;
    }

    /**
     * get Record Label
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * get started at time
     * @return int
     */
    public function getStartTime(): int
    {
        return $this->started_at;
    }

    /**
     * get ended at time
     * @return int
     */
    public function getEndTime(): int
    {
        return $this->end_at;
    }

    /**
     * get record duration time
     * @return int
     */
    public function getDuration(): int
    {
        return $this->end_at - $this->started_at;
    }

    /**
     * get record usage memory size
     * @return int
     */
    public function getMemory(): int
    {
        return $this->usage_memory;
    }

    /**
     * get cumulative memory size
     * @return int
     */
    public function getAccumulateMemory(): int
    {
        return $this->accumulated_usage_memory + $this->usage_memory;
    }

    public function __toString()
    {
        return sprintf(
            'Usage Memory : %s | Accumulate Usage Memory : %s | Record Duration : %d ms -> %s',
            self::readableSize($this->getMemory()),
            self::readableSize($this->getAccumulateMemory()),
            $this->getDuration(),
            $this->getLabel()
        );
    }
}
