<?php

declare(strict_types=1);

namespace AdamWojs\PhpCsFixerPhpdocForceFQCN\FQCN;

use AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\NamespaceInfo;

class FQCNResolver
{
    /** @var \AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\NamespaceInfo */
    private $namespaceInfo;

    /**
     * @param \AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\NamespaceInfo $namespaceInfo
     */
    public function __construct(NamespaceInfo $namespaceInfo)
    {
        $this->namespaceInfo = $namespaceInfo;
    }

    /**
     * Tries to resolve FQCN based on short name and  imports of the current namespace.
     *
     * @param string $className
     *
     * @return string
     */
    public function resolveFQCN(string $className): string
    {
        $shortName = $this->getShortName($className);

        if ($this->namespaceInfo->hasImport($shortName)) {
            $className = $this->namespaceInfo->getImport($shortName)->getFullName();
            if (strpos($className, '\\') !== 0) {
                $className = '\\' . $className;
            }
        }

        return $className;
    }


    /**
     * Returns last part of the full qualified class name.
     *
     * @param string $name
     *
     * @return string
     */
    private function getShortName(string $name): string
    {
        $chunks = explode('\\', $name);
        if (count($chunks) > 1) {
            $name = end($chunks);
        }

        return $name;
    }
}
