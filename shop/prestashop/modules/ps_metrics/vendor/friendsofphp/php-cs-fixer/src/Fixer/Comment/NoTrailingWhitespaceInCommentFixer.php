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
namespace ps_metrics_module_v4_0_6\PhpCsFixer\Fixer\Comment;

use ps_metrics_module_v4_0_6\PhpCsFixer\AbstractFixer;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\CodeSample;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinition;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\Preg;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Token;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Tokens;
/**
 * @author Dariusz Rumiński <dariusz.ruminski@gmail.com>
 */
final class NoTrailingWhitespaceInCommentFixer extends AbstractFixer
{
    /**
     * {@inheritdoc}
     */
    public function getDefinition() : FixerDefinitionInterface
    {
        return new FixerDefinition('There MUST be no trailing spaces inside comment or PHPDoc.', [new CodeSample('<?php
// This is ' . '
// a comment. ' . '
')]);
    }
    /**
     * {@inheritdoc}
     *
     * Must run after PhpdocNoUselessInheritdocFixer.
     */
    public function getPriority() : int
    {
        return 0;
    }
    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens) : bool
    {
        return $tokens->isAnyTokenKindsFound([\T_COMMENT, \T_DOC_COMMENT]);
    }
    /**
     * {@inheritdoc}
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens) : void
    {
        foreach ($tokens as $index => $token) {
            if ($token->isGivenKind(\T_DOC_COMMENT)) {
                $tokens[$index] = new Token([\T_DOC_COMMENT, Preg::replace('/(*ANY)[\\h]+$/m', '', $token->getContent())]);
                continue;
            }
            if ($token->isGivenKind(\T_COMMENT)) {
                if (\str_starts_with($token->getContent(), '/*')) {
                    $tokens[$index] = new Token([\T_COMMENT, Preg::replace('/(*ANY)[\\h]+$/m', '', $token->getContent())]);
                } elseif (isset($tokens[$index + 1]) && $tokens[$index + 1]->isWhitespace()) {
                    $trimmedContent = \ltrim($tokens[$index + 1]->getContent(), " \t");
                    $tokens->ensureWhitespaceAtIndex($index + 1, 0, $trimmedContent);
                }
            }
        }
    }
}
