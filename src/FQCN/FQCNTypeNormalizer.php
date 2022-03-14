<?php

declare(strict_types=1);

namespace AdamWojs\PhpCsFixerPhpdocForceFQCN\FQCN;

use AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\NamespaceInfo;

class FQCNTypeNormalizer
{
    public const BUILD_IN_TYPES = [
        'string',
        'integer',
        'int',
        'boolean',
        'bool',
        'float',
        'double',
        'object',
        'mixed',
        'array',
        'resource',
        'void',
        'null',
        'callback',
        'false',
        'true',
        'self'
    ];

    public const SPECIAL_CHARS = ['(', ')', '{', '}', '[', ']', '<', '>', '|', '&', ',', ' ', ':', "'", '"'];

    /**
     * @param \AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\NamespaceInfo $namespaceInfo
     * @param string $type
     *
     * @return string
     */
    public function normalizeType(NamespaceInfo $namespaceInfo, string $type): string
    {
        $typeToCheck = '';
        $typeNew = '';

        for ($i = 0; $i < strlen($type); $i++) {
            if (in_array($type[$i], static::SPECIAL_CHARS)) {
                if ($typeToCheck !== '') {
                    $typeNew .= $this->normalizeSingleType($namespaceInfo, $typeToCheck);
                    $typeToCheck = '';
                }

                $typeNew .= $type[$i];

                continue;
            }

            $typeToCheck .= $type[$i];
        }

        if ($typeToCheck !== '') {
            $typeNew .= $this->normalizeSingleType($namespaceInfo, $typeToCheck);
        }

        return $typeNew;
    }

    /**
     * @param \AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\NamespaceInfo $namespaceInfo
     * @param string $type
     *
     * @return string
     */
    public function normalizeSingleType(NamespaceInfo $namespaceInfo, string $type): string
    {
        if ($this->isBuildInType($type) || $this->isFQCN($type)) {
            return $type;
        }

        return (new FQCNResolver($namespaceInfo))->resolveFQCN($type);
    }


    /**
     * Returns true is given identifier is FQCN.
     *
     * @param string $id
     *
     * @return bool
     */
    private function isFQCN(string $id): bool
    {
        return strpos($id, '\\') === 0;
    }

    /**
     * Returns true if given identifier is build-in type
     *
     * @see http://docs.phpdoc.org/references/phpdoc/types.html#keyword
     *
     * @param string $id
     *
     * @return bool
     */
    private function isBuildInType(string $id): bool
    {
        return in_array($id, self::BUILD_IN_TYPES);
    }
}
