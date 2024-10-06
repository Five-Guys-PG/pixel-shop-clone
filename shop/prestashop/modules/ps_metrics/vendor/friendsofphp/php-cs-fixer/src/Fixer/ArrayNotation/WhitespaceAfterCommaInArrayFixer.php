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
namespace ps_metrics_module_v4_0_6\PhpCsFixer\Fixer\ArrayNotation;

use ps_metrics_module_v4_0_6\PhpCsFixer\AbstractFixer;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\CodeSample;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinition;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\CT;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Token;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Tokens;
/**
 * @author Adam Marczuk <adam@marczuk.info>
 */
final class WhitespaceAfterCommaInArrayFixer extends AbstractFixer
{
    /**
     * {@inheritdoc}
     */
    public function getDefinition() : FixerDefinitionInterface
    {
        return new FixerDefinition('In array declaration, there MUST be a whitespace after each comma.', [new CodeSample("<?php\n\$sample = array(1,'a',\$b,);\n")]);
    }
    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens) : bool
    {
        return $tokens->isAnyTokenKindsFound([\T_ARRAY, CT::T_ARRAY_SQUARE_BRACE_OPEN]);
    }
    /**
     * {@inheritdoc}
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens) : void
    {
        $tokensToInsert = [];
        for ($index = $tokens->count() - 1; $index >= 0; --$index) {
            if (!$tokens[$index]->isGivenKind([\T_ARRAY, CT::T_ARRAY_SQUARE_BRACE_OPEN])) {
                continue;
            }
            if ($tokens[$index]->isGivenKind(CT::T_ARRAY_SQUARE_BRACE_OPEN)) {
                $startIndex = $index;
                $endIndex = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_ARRAY_SQUARE_BRACE, $startIndex);
            } else {
                $startIndex = $tokens->getNextTokenOfKind($index, ['(']);
                $endIndex = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_PARENTHESIS_BRACE, $startIndex);
            }
            for ($i = $endIndex - 1; $i > $startIndex; --$i) {
                $i = $this->skipNonArrayElements($i, $tokens);
                if ($tokens[$i]->equals(',') && !$tokens[$i + 1]->isWhitespace()) {
                    $tokensToInsert[$i + 1] = new Token([\T_WHITESPACE, ' ']);
                }
            }
        }
        if ([] !== $tokensToInsert) {
            $tokens->insertSlices($tokensToInsert);
        }
    }
    /**
     * Method to move index over the non-array elements like function calls or function declarations.
     *
     * @return int New index
     */
    private function skipNonArrayElements(int $index, Tokens $tokens) : int
    {
        if ($tokens[$index]->equals('}')) {
            return $tokens->findBlockStart(Tokens::BLOCK_TYPE_CURLY_BRACE, $index);
        }
        if ($tokens[$index]->equals(')')) {
            $startIndex = $tokens->findBlockStart(Tokens::BLOCK_TYPE_PARENTHESIS_BRACE, $index);
            $startIndex = $tokens->getPrevMeaningfulToken($startIndex);
            if (!$tokens[$startIndex]->isGivenKind([\T_ARRAY, CT::T_ARRAY_SQUARE_BRACE_OPEN])) {
                return $startIndex;
            }
        }
        return $index;
    }
}
