<?php

declare (strict_types=1);
/*
 * This file is part of PHP CS Fixer.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace ps_metrics_module_v4_0_6\PhpCsFixer\Fixer\Operator;

use ps_metrics_module_v4_0_6\PhpCsFixer\AbstractFixer;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\CodeSample;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinition;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\CT;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Token;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Tokens;
/**
 * @author Dariusz Rumiński <dariusz.ruminski@gmail.com>
 */
final class NewWithBracesFixer extends AbstractFixer
{
    /**
     * {@inheritdoc}
     */
    public function getDefinition() : FixerDefinitionInterface
    {
        return new FixerDefinition('All instances created with new keyword must be followed by braces.', [new CodeSample("<?php \$x = new X;\n")]);
    }
    /**
     * {@inheritdoc}
     *
     * Must run before ClassDefinitionFixer.
     */
    public function getPriority() : int
    {
        return 37;
    }
    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens) : bool
    {
        return $tokens->isTokenKindFound(\T_NEW);
    }
    /**
     * {@inheritdoc}
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens) : void
    {
        static $nextTokenKinds = null;
        if (null === $nextTokenKinds) {
            $nextTokenKinds = ['?', ';', ',', '(', ')', '[', ']', ':', '<', '>', '+', '-', '*', '/', '%', '&', '^', '|', [\T_CLASS], [\T_IS_SMALLER_OR_EQUAL], [\T_IS_GREATER_OR_EQUAL], [\T_IS_EQUAL], [\T_IS_NOT_EQUAL], [\T_IS_IDENTICAL], [\T_IS_NOT_IDENTICAL], [\T_CLOSE_TAG], [\T_LOGICAL_AND], [\T_LOGICAL_OR], [\T_LOGICAL_XOR], [\T_BOOLEAN_AND], [\T_BOOLEAN_OR], [\T_SL], [\T_SR], [\T_INSTANCEOF], [\T_AS], [\T_DOUBLE_ARROW], [\T_POW], [\T_SPACESHIP], [CT::T_ARRAY_SQUARE_BRACE_OPEN], [CT::T_ARRAY_SQUARE_BRACE_CLOSE], [CT::T_BRACE_CLASS_INSTANTIATION_OPEN], [CT::T_BRACE_CLASS_INSTANTIATION_CLOSE]];
            if (\defined('T_AMPERSAND_FOLLOWED_BY_VAR_OR_VARARG')) {
                // @TODO: drop condition when PHP 8.1+ is required
                $nextTokenKinds[] = [\T_AMPERSAND_FOLLOWED_BY_VAR_OR_VARARG];
                $nextTokenKinds[] = [\T_AMPERSAND_NOT_FOLLOWED_BY_VAR_OR_VARARG];
            }
        }
        for ($index = $tokens->count() - 3; $index > 0; --$index) {
            if (!$tokens[$index]->isGivenKind(\T_NEW)) {
                continue;
            }
            $nextIndex = $tokens->getNextTokenOfKind($index, $nextTokenKinds);
            $nextToken = $tokens[$nextIndex];
            // new anonymous class definition
            if ($nextToken->isGivenKind(\T_CLASS)) {
                if (!$tokens[$tokens->getNextMeaningfulToken($nextIndex)]->equals('(')) {
                    $this->insertBracesAfter($tokens, $nextIndex);
                }
                continue;
            }
            // entrance into array index syntax - need to look for exit
            while ($nextToken->equals('[') || $nextToken->isGivenKind(CT::T_ARRAY_INDEX_CURLY_BRACE_OPEN)) {
                $nextIndex = $tokens->findBlockEnd(Tokens::detectBlockType($nextToken)['type'], $nextIndex) + 1;
                $nextToken = $tokens[$nextIndex];
            }
            // new statement has a gap in it - advance to the next token
            if ($nextToken->isWhitespace()) {
                $nextIndex = $tokens->getNextNonWhitespace($nextIndex);
                $nextToken = $tokens[$nextIndex];
            }
            // new statement with () - nothing to do
            if ($nextToken->equals('(') || $nextToken->isObjectOperator()) {
                continue;
            }
            $this->insertBracesAfter($tokens, $tokens->getPrevMeaningfulToken($nextIndex));
        }
    }
    private function insertBracesAfter(Tokens $tokens, int $index) : void
    {
        $tokens->insertAt(++$index, [new Token('('), new Token(')')]);
    }
}
