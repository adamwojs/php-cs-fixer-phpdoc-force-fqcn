<?php

return (new PhpCsFixer\Config())
    ->registerCustomFixers([
        new \AdamWojs\PhpCsFixerPhpdocForceFQCN\Fixer\Phpdoc\ForceFQCNFixer()
    ])
    ->setRules([
        'AdamWojs/phpdoc_force_fqcn_fixer' => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()->path(__DIR__.  'Original.php')
    );
