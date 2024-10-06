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
use ps_metrics_module_v4_0_6\PhpCsFixer\Fixer\ConfigurableFixerInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerConfigurationResolver;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\CodeSample;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinition;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\Preg;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Token;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Tokens;
final class SpaceAfterSemicolonFixer extends AbstractFixer implements ConfigurableFixerInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDefinition() : FixerDefinitionInterface
    {
        return new FixerDefinition('Fix whitespace after a semicolon.', [new CodeSample("<?php\n                        sample();     \$test = 1;\n                        sample();\$test = 2;\n                        for ( ;;++\$sample) {\n                        }\n"), new CodeSample("<?php\nfor (\$i = 0; ; ++\$i) {\n}\n", ['remove_in_empty_for_expressions' => \true])]);
    }
    /**
     * {@inheritdoc}
     *
     * Must run after CombineConsecutiveUnsetsFixer, MultilineWhitespaceBeforeSemicolonsFixer, NoEmptyStatementFixer, OrderedClassElementsFixer, SingleImportPerStatementFixer, SingleTraitInsertPerStatementFixer.
     */
    public function getPriority() : int
    {
        return -1;
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
    protected function createConfigurationDefinition() : FixerConfigurationResolverInterface
    {
        return new FixerConfigurationResolver([(new FixerOptionBuilder('remove_in_empty_for_expressions', 'Whether spaces should be removed for empty `for` expressions.'))->setAllowedTypes(['bool'])->setDefault(\false)->getOption()]);
    }
    /**
     * {@inheritdoc}
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens) : void
    {
        $insideForParenthesesUntil = null;
        for ($index = 0, $max = \count($tokens) - 1; $index < $max; ++$index) {
            if (\true === $this->configuration['remove_in_empty_for_expressions']) {
                if ($tokens[$index]->isGivenKind(\T_FOR)) {
                    $index = $tokens->getNextMeaningfulToken($index);
                    $insideForParenthesesUntil = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_PARENTHESIS_BRACE, $index);
                    continue;
                }
                if ($index === $insideForParenthesesUntil) {
                    $insideForParenthesesUntil = null;
                    continue;
                }
            }
            if (!$tokens[$index]->equals(';')) {
                continue;
            }
            if (!$tokens[$index + 1]->isWhitespace()) {
                if (!$tokens[$index + 1]->equalsAny([')', [\T_INLINE_HTML]]) && (\false === $this->configuration['remove_in_empty_for_expressions'] || !$tokens[$index + 1]->equals(';'))) {
                    $tokens->insertAt($index + 1, new Token([\T_WHITESPACE, ' ']));
                    ++$max;
                }
                continue;
            }
            if (null !== $insideForParenthesesUntil && ($tokens[$index + 2]->equals(';') || $index + 2 === $insideForParenthesesUntil) && !Preg::match('/\\R/', $tokens[$index + 1]->getContent())) {
                $tokens->clearAt($index + 1);
                continue;
            }
            if (isset($tokens[$index + 2]) && !$tokens[$index + 1]->equals([\T_WHITESPACE, ' ']) && $tokens[$index + 1]->isWhitespace(" \t") && !$tokens[$index + 2]->isComment() && !$tokens[$index + 2]->equals(')')) {
                $tokens[$index + 1] = new Token([\T_WHITESPACE, ' ']);
            }
        }
    }
}
