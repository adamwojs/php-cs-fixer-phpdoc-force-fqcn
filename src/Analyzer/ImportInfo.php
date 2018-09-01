<?php

declare(strict_types=1);

namespace AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer;

final class ImportInfo
{
    /** @var string */
    private $fullName;

    /** @var string */
    private $shortName;

    /** @var bool */
    private $aliased;

    /** @var \AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\Range */
    private $declaration;

    /**
     * @param string $fullName
     * @param string $shortName
     * @param bool $aliased
     * @param \AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\Range $declaration
     */
    public function __construct(string $fullName, string $shortName, bool $aliased, Range $declaration)
    {
        $this->fullName = $fullName;
        $this->shortName = $shortName;
        $this->aliased = $aliased;
        $this->declaration = $declaration;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     * @return string
     */
    public function getShortName(): string
    {
        return $this->shortName;
    }

    /**
     * @return bool
     */
    public function isAliased(): bool
    {
        return $this->aliased;
    }

    /**
     * @return \AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\Range
     */
    public function getDeclaration(): Range
    {
        return $this->declaration;
    }
}
