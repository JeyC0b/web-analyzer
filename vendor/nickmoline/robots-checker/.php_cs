<?php
$header = <<<'EOF'
Configuration for php-cs-fixer PSR2
EOF;
Symfony\CS\Fixer\Contrib\HeaderCommentFixer::setHeader($header);
return Symfony\CS\Config\Config::create()
    ->level(Symfony\CS\FixerInterface::PSR2_LEVEL)
    ->fixers([
        'concat_with_spaces',
        'elseif',
        'eof_ending',
        'remove_leading_slash_use',
        'phpdoc_no_empty_return',
        'phpdoc_params',
        'phpdoc_to_comment',
        'phpdoc_order',
        'function_call_space',
        'function_declaration',
        'indentation',
        'line_after_namespace',
        'lowercase_constants',
        'method_argument_space',
        'trailing_spaces',
        'new_with_braces'
    ])
    ->finder(
        Symfony\CS\Finder\DefaultFinder::create()
            ->in(__DIR__.'/src')
            ->in(__DIR__.'/tests')
    );
