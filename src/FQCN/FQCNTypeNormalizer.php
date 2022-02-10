<?php

declare(strict_types=1);

namespace AdamWojs\PhpCsFixerPhpdocForceFQCN\FQCN;

use AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\NamespaceInfo;

class FQCNTypeNormalizer
{
    const BUILD_IN_TYPES = [
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

    const SPLITTABLE_CHARS = ['(', ')', '{', '}', '<', '>', '|', '&', ',', ' ', ':', "'", '"'];

    /**
     * @param \AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\NamespaceInfo $namespaceInfo
     * @param string $type
     *
     * @return string
     */
    public function normalizeType(NamespaceInfo $namespaceInfo, string $type): string
    {
        return $this->normalizeTypeAfterSplitting($namespaceInfo, [$type], static::SPLITTABLE_CHARS)[0];
    }

    /**
     * @param \AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\NamespaceInfo $namespaceInfo
     * @param string[] $types
     * @param string[]
     *
     * @return array
     */
    public function normalizeTypeAfterSplitting(NamespaceInfo $namespaceInfo, array $types, array $splittableChars): array
    {
        if($splittableChars === []) {

            foreach($types as $typeKey => $type) {
            
                $types[$typeKey] = $this->normalizeSingleType($namespaceInfo, $type);

            }

            return $types;

        }

        $splitChar = array_pop($splittableChars);

        foreach($types as $typeKey => $type) {

            $splittedType = explode($splitChar, $type);

            $normalized = $this->normalizeTypeAfterSplitting($namespaceInfo, $splittedType, $splittableChars);
        
            $types[$typeKey] = implode($splitChar, $normalized);

        }

        return $types;
    }

    /**
     * @param \AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\NamespaceInfo $namespaceInfo
     * @param string $type
     *
     * @return string
     */
    public function normalizeSingleType(NamespaceInfo $namespaceInfo, string $type): string
    {
        if ('[]' === substr($type, -2)) {
            return $this->normalizeSingleType($namespaceInfo, substr($type, 0, -2)) . '[]';
        }

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
