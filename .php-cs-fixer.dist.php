<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'concat_space' => ['spacing' => 'one'],
        'no_unused_imports' => true,
        'return_assignment' => true,
        'no_unneeded_control_parentheses' => [
            'statements' => [
                'break', 'clone', 'continue', 'echo_print', 'negative_instanceof',
                'others', 'return', 'switch_case', 'yield', 'yield_from'
            ]
        ]
    ])
    ->setFinder($finder);
