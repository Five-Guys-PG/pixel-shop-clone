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

use ps_metrics_module_v4_0_6\PhpCsFixer\Fixer\AbstractIncrementOperatorFixer;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\CodeSample;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinition;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\CT;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Token;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Tokens;
/**
 * @author ntzm
 */
final class StandardizeIncrementFixer extends AbstractIncrementOperatorFixer
{
    private const EXPRESSION_END_TOKENS = [';', ')', ']', ',', ':', [CT::T_DYNAMIC_PROP_BRACE_CLOSE], [CT::T_DYNAMIC_VAR_BRACE_CLOSE], [\T_CLOSE_TAG]];
    /**
     * {@inheritdoc}
     */
    public function getDefinition() : FixerDefinitionInterface
    {
        return new FixerDefinition('Increment and decrement operators should be used if possible.', [new CodeSample("<?php\n\$i += 1;\n"), new CodeSample("<?php\n\$i -= 1;\n")]);
    }
    /**
     * {@inheritdoc}
     *
     * Must run before IncrementStyleFixer.
     */
    public function getPriority() : int
    {
        return 1;
    }
    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens) : bool
    {
        return $tokens->isAnyTokenKindsFound([\T_PLUS_EQUAL, \T_MINUS_EQUAL]);
    }
    /**
     * {@inheritdoc}
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens) : void
    {
        for ($index = $tokens->count() - 1; $index > 0; --$index) {
            $expressionEnd = $tokens[$index];
            if (!$expressionEnd->equalsAny(self::EXPRESSION_END_TOKENS)) {
                continue;
            }
            $numberIndex = $tokens->getPrevMeaningfulToken($index);
            $number = $tokens[$numberIndex];
            if (!$number->isGivenKind(\T_LNUMBER) || '1' !== $number->getContent()) {
                continue;
            }
            $operatorIndex = $tokens->getPrevMeaningfulToken($numberIndex);
            $operator = $tokens[$operatorIndex];
            if (!$operator->isGivenKind([\T_PLUS_EQUAL, \T_MINUS_EQUAL])) {
                continue;
            }
            $startIndex = $this->findStart($tokens, $operatorIndex);
            $this->clearRangeLeaveComments($tokens, $tokens->getPrevMeaningfulToken($operatorIndex) + 1, $numberIndex);
            $tokens->insertAt($startIndex, new Token($operator->isGivenKind(\T_PLUS_EQUAL) ? [\T_INC, '++'] : [\T_DEC, '--']));
        }
    }
    /**
     * Clear tokens in the given range unless they are comments.
     */
    private function clearRangeLeaveComments(Tokens $tokens, int $indexStart, int $indexEnd) : void
    {
        for ($i = $indexStart; $i <= $indexEnd; ++$i) {
            $token = $tokens[$i];
            if ($token->isComment()) {
                continue;
            }
            if ($token->isWhitespace("\n\r")) {
                continue;
            }
            $tokens->clearAt($i);
        }
    }
}
