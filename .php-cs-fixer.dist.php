<?php

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(false)
    ->setHideProgress(true)
    ->setUsingCache(false)
    ->setRules([
        '@PSR12' => true,
        'array_indentation' => true,
        'method_chaining_indentation' => true
    ])
    ->setIndent("    ") // 4 space
;