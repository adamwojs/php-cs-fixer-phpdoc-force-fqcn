<?php

declare(strict_types=1);

namespace AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer;

final class Range
{
    /** @var int */
    private $startIndex;

    /** @var int */
    private $endIndex;

    /**
     * @param int $startIndex
     * @param int $endIndex
     */
    public function __construct(int $startIndex, ?int $endIndex)
    {
        $this->startIndex = $startIndex;
        $this->endIndex = $endIndex;
    }

    /**
     * @return int
     */
    public function getStartIndex(): int
    {
        return $this->startIndex;
    }

    /**
     * @return int
     */
    public function getEndIndex(): int
    {
        return $this->endIndex;
    }

    /**
     * Returns true if given index is in range.
     *
     * @param int $index
     * @return bool
     */
    public function inRange(int $index): bool
    {
        if ($index <= $this->startIndex) {
            return false;
        }

        if ($index >= $this->endIndex && $this->endIndex !== null) {
            return false;
        }

        return true;
    }

    public function __toString()
    {
        return sprintf("[%s,%s]", $this->startIndex, $this->endIndex ?? 'INF');
    }
}
