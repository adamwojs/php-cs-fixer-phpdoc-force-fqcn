<?php

declare(strict_types=1);

namespace AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer;

use RuntimeException;

final class NamespaceInfo
{
    /** @var string */
    private $name;

    /** @var \AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\Range */
    private $scope;

    /** @var \AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\ImportInfo[] */
    private $imports;

    /**
     * @param string $name
     * @param \AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\Range $scope
     * @param \AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\ImportInfo[] $imports
     */
    public function __construct(string $name, Range $scope, array $imports)
    {
        $this->name = $name;
        $this->scope = $scope;
        $this->imports = $imports;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return \AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\Range
     */
    public function getScope(): Range
    {
        return $this->scope;
    }

    /**
     * @return \AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\ImportInfo[]
     */
    public function getImports(): array
    {
        return $this->imports;
    }

    /**
     * Return true if given class name is imported into namespace.
     *
     * @param string $class
     *
     * @return bool
     */
    public function hasImport(string $class): bool
    {
        return isset($this->imports[$class]);
    }

    /**
     * Return information about given class import.
     *
     * @param string $class
     *
     * @return \AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\ImportInfo
     */
    public function getImport(string $class): ImportInfo
    {
        if ($this->hasImport($class)) {
            return $this->imports[$class];
        }

        throw new RuntimeException("$class is not imported into current namespace.");
    }
}
