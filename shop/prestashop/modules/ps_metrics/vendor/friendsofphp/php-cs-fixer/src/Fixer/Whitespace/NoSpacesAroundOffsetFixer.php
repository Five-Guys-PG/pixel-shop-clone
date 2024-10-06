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
namespace ps_metrics_module_v4_0_6\PhpCsFixer\Fixer\Whitespace;

use ps_metrics_module_v4_0_6\PhpCsFixer\AbstractFixer;
use ps_metrics_module_v4_0_6\PhpCsFixer\Fixer\ConfigurableFixerInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\AllowedValueSubset;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerConfigurationResolver;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\CodeSample;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinition;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\CT;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Tokens;
/**
 * @author Javier Spagnoletti <phansys@gmail.com>
 */
final class NoSpacesAroundOffsetFixer extends AbstractFixer implements ConfigurableFixerInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDefinition() : FixerDefinitionInterface
    {
        return new FixerDefinition('There MUST NOT be spaces around offset braces.', [new CodeSample("<?php\n\$sample = \$b [ 'a' ] [ 'b' ];\n"), new CodeSample("<?php\n\$sample = \$b [ 'a' ] [ 'b' ];\n", ['positions' => ['inside']]), new CodeSample("<?php\n\$sample = \$b [ 'a' ] [ 'b' ];\n", ['positions' => ['outside']])]);
    }
    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens) : bool
    {
        return $tokens->isAnyTokenKindsFound(['[', CT::T_ARRAY_INDEX_CURLY_BRACE_OPEN]);
    }
    /**
     * {@inheritdoc}
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens) : void
    {
        foreach ($tokens as $index => $token) {
            if (!$token->equalsAny(['[', [CT::T_ARRAY_INDEX_CURLY_BRACE_OPEN]])) {
                continue;
            }
            if (\in_array('inside', $this->configuration['positions'], \true)) {
                if ($token->equals('[')) {
                    $endIndex = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_INDEX_SQUARE_BRACE, $index);
                } else {
                    $endIndex = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_ARRAY_INDEX_CURLY_BRACE, $index);
                }
                // remove space after opening `[` or `{`
                if ($tokens[$index + 1]->isWhitespace(" \t")) {
                    $tokens->clearAt($index + 1);
                }
                // remove space before closing `]` or `}`
                if ($tokens[$endIndex - 1]->isWhitespace(" \t")) {
                    $tokens->clearAt($endIndex - 1);
                }
            }
            if (\in_array('outside', $this->configuration['positions'], \true)) {
                $prevNonWhitespaceIndex = $tokens->getPrevNonWhitespace($index);
                if ($tokens[$prevNonWhitespaceIndex]->isComment()) {
                    continue;
                }
                $tokens->removeLeadingWhitespace($index);
            }
        }
    }
    /**
     * {@inheritdoc}
     */
    protected function createConfigurationDefinition() : FixerConfigurationResolverInterface
    {
        $values = ['inside', 'outside'];
        return new FixerConfigurationResolver([(new FixerOptionBuilder('positions', 'Whether spacing should be fixed inside and/or outside the offset braces.'))->setAllowedTypes(['array'])->setAllowedValues([new AllowedValueSubset($values)])->setDefault($values)->getOption()]);
    }
}
