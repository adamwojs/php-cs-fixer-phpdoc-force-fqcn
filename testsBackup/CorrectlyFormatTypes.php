<?php
declare(strict_types=1);

namespace AdamWojs\PhpCsFixerPhpdocForceFQCN\Tests;

use PhpCsFixer\Console\Application;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

final class CorrectlyFormatTypes extends TestCase
{
    public function testCanBeCreatedFromValidEmailAddress(): void
    {
        $application = new Application();

        $application->setAutoExit(false);

        $exitCode = $application->run(
            new ArrayInput([
                'command' => 'fix',
                'path' => [__DIR__.'/../vendor/laravel/framework'],
                '--config' => __DIR__.'/__fixtures__/.php-cs-fixer.dist.php',
                '--dry-run' => true,
                '--diff' => true,
                '--verbose' => true,
            ]),
            $output = new BufferedOutput()
        );

        $this->assertEquals(0, $exitCode);
    }
}

