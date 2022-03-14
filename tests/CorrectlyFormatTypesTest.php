<?php
declare(strict_types=1);

namespace AdamWojs\PhpCsFixerPhpdocForceFQCN\Tests;

use AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\ImportInfo;
use AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\NamespaceInfo;
use AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\Range;
use AdamWojs\PhpCsFixerPhpdocForceFQCN\FQCN\FQCNTypeNormalizer;
use PhpCsFixer\Console\Application;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

final class CorrectlyFormatTypesTest extends TestCase
{
    public function testCanFormatSingleTypeCorrectly(): void
    {
        $typeExpected = "\CustomNamespace\TestClass";

        $typeOriginal = "TestClass";

        $namespaceInfo = new NamespaceInfo('', new Range(0, 0), [
            'TestClass' => new ImportInfo('CustomNamespace\TestClass', 'TestClass', false, new Range(0,0)),
        ]);

        $typeActual = (new FQCNTypeNormalizer)->normalizeType($namespaceInfo, $typeOriginal);

        $this->assertEquals($typeExpected, $typeActual);
    }

    public function testCanFormatComplexTypeCorrectly(): void
    {
        $typeExpected = "\CustomNamespace\TestClass|(\CustomNamespace\TestClass&\CustomNamespace\TestClass<\CustomNamespace\TestClass, \CustomNamespace\TestClass>)|\CustomNamespace\TestClass{string: int}";

        $typeOriginal = "TestClass|(TestClass&TestClass<TestClass, TestClass>)|TestClass{string: int}";

        $namespaceInfo = new NamespaceInfo('', new Range(0, 0), [
            'TestClass' => new ImportInfo('CustomNamespace\TestClass', 'TestClass', false, new Range(0,0)),
        ]);

        $typeActual = (new FQCNTypeNormalizer)->normalizeType($namespaceInfo, $typeOriginal);

        $this->assertEquals($typeExpected, $typeActual);
    }
}

