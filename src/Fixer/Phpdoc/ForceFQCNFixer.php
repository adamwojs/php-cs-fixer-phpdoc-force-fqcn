<?php

declare(strict_types=1);

namespace AdamWojs\PhpCsFixerPhpdocForceFQCN\Fixer\Phpdoc;

use AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\NamespaceAnalyzer;
use AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\NamespaceInfo;
use AdamWojs\PhpCsFixerPhpdocForceFQCN\FQCN\FQCNTypeNormalizer;
use PhpCsFixer\DocBlock\Annotation;
use PhpCsFixer\DocBlock\DocBlock;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use SplFileInfo;

class ForceFQCNFixer implements FixerInterface
{
    /** @var \AdamWojs\PhpCsFixerPhpdocForceFQCN\FQCN\FQCNTypeNormalizer */
    private $normalizer;

    public function __construct()
    {
        $this->normalizer = new FQCNTypeNormalizer();
    }

    /**
     * {@inheritdoc}
     */
    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition('FQCN should be used in phpdoc', []);
    }

    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens): bool
    {
        return $tokens->isTokenKindFound(T_DOC_COMMENT);
    }

    /**
     * {@inheritdoc}
     */
    public function isRisky(): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function fix(SplFileInfo $file, Tokens $tokens): void
    {
        $namespaces = (new NamespaceAnalyzer($tokens))->getNamespaces();

        if (\PHP_VERSION_ID < 80000) {
            $tokens->rewind();
        }

        foreach ($tokens as $index => $token) {
            if ($token->isGivenKind(T_DOC_COMMENT)) {
                $currentNamespace = null;
                foreach ($namespaces as $namespace) {
                    if ($namespace->getScope()->inRange($index)) {
                        $currentNamespace = $namespace;
                        break;
                    }
                }

                if ($currentNamespace === null) {
                    continue;
                }

                $docBlock = new DocBlock($token->getContent());

                $annotations = $docBlock->getAnnotationsOfType(Annotation::getTagsWithTypes());
                if (empty($annotations)) {
                    continue;
                }

                foreach ($annotations as $annotation) {
                    $this->fixAnnotation($currentNamespace, $annotation);
                }

                $tokens[$index] = new Token([T_DOC_COMMENT, $docBlock->getContent()]);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'AdamWojs/phpdoc_force_fqcn_fixer';
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority(): int
    {
        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(SplFileInfo $file): bool
    {
        return true;
    }

    /**
     * @param \AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\NamespaceInfo $currentNamespace
     * @param \PhpCsFixer\DocBlock\Annotation $annotation
     */
    private function fixAnnotation(NamespaceInfo $currentNamespace, Annotation $annotation): void
    {
        $types = $annotation->getTypes();
        foreach ($types as $i => $type) {
            $types[$i] = $this->normalizer->normalizeType($currentNamespace, $type);
        }

        if ($types !== $annotation->getTypes()) {
            $annotation->setTypes($types);
        }
    }
}
