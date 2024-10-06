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
namespace ps_metrics_module_v4_0_6\PhpCsFixer\Fixer\Semicolon;

use ps_metrics_module_v4_0_6\PhpCsFixer\AbstractFixer;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\CodeSample;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinition;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Tokens;
/**
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
final class NoSinglelineWhitespaceBeforeSemicolonsFixer extends AbstractFixer
{
    /**
     * {@inheritdoc}
     */
    public function getDefinition() : FixerDefinitionInterface
    {
        return new FixerDefinition('Single-line whitespace before closing semicolon are prohibited.', [new CodeSample("<?php \$this->foo() ;\n")]);
    }
    /**
     * {@inheritdoc}
     *
     * Must run after CombineConsecutiveIssetsFixer, FunctionToConstantFixer, NoEmptyStatementFixer, SimplifiedIfReturnFixer, SingleImportPerStatementFixer.
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
        return $tokens->isTokenKindFound(';');
    }
    /**
     * {@inheritdoc}
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens) : void
    {
        foreach ($tokens as $index => $token) {
            if (!$token->equals(';') || !$tokens[$index - 1]->isWhitespace(" \t")) {
                continue;
            }
            if ($tokens[$index - 2]->equals(';')) {
                // do not remove all whitespace before the semicolon because it is also whitespace after another semicolon
                $tokens->ensureWhitespaceAtIndex($index - 1, 0, ' ');
            } elseif (!$tokens[$index - 2]->isComment()) {
                $tokens->clearAt($index - 1);
            }
        }
    }
}
