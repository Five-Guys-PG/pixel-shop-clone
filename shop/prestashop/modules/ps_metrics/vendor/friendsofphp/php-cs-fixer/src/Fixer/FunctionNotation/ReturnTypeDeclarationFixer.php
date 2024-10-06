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
namespace ps_metrics_module_v4_0_6\PhpCsFixer\Fixer\FunctionNotation;

use ps_metrics_module_v4_0_6\PhpCsFixer\AbstractFixer;
use ps_metrics_module_v4_0_6\PhpCsFixer\Fixer\ConfigurableFixerInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerConfigurationResolver;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\CodeSample;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinition;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\CT;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Token;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Tokens;
/**
 * @author Dariusz Rumiński <dariusz.ruminski@gmail.com>
 */
final class ReturnTypeDeclarationFixer extends AbstractFixer implements ConfigurableFixerInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDefinition() : FixerDefinitionInterface
    {
        return new FixerDefinition('There should be one or no space before colon, and one space after it in return type declarations, according to configuration.', [new CodeSample("<?php\nfunction foo(int \$a):string {};\n"), new CodeSample("<?php\nfunction foo(int \$a):string {};\n", ['space_before' => 'none']), new CodeSample("<?php\nfunction foo(int \$a):string {};\n", ['space_before' => 'one'])], 'Rule is applied only in a PHP 7+ environment.');
    }
    /**
     * {@inheritdoc}
     *
     * Must run after PhpdocToReturnTypeFixer, VoidReturnFixer.
     */
    public function getPriority() : int
    {
        return -17;
    }
    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens) : bool
    {
        return $tokens->isTokenKindFound(CT::T_TYPE_COLON);
    }
    /**
     * {@inheritdoc}
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens) : void
    {
        $oneSpaceBefore = 'one' === $this->configuration['space_before'];
        for ($index = 0, $limit = $tokens->count(); $index < $limit; ++$index) {
            if (!$tokens[$index]->isGivenKind(CT::T_TYPE_COLON)) {
                continue;
            }
            $previousIndex = $index - 1;
            $previousToken = $tokens[$previousIndex];
            if ($previousToken->isWhitespace()) {
                if (!$tokens[$tokens->getPrevNonWhitespace($index - 1)]->isComment()) {
                    if ($oneSpaceBefore) {
                        $tokens[$previousIndex] = new Token([\T_WHITESPACE, ' ']);
                    } else {
                        $tokens->clearAt($previousIndex);
                    }
                }
            } elseif ($oneSpaceBefore) {
                $tokenWasAdded = $tokens->ensureWhitespaceAtIndex($index, 0, ' ');
                if ($tokenWasAdded) {
                    ++$limit;
                }
                ++$index;
            }
            ++$index;
            $tokenWasAdded = $tokens->ensureWhitespaceAtIndex($index, 0, ' ');
            if ($tokenWasAdded) {
                ++$limit;
            }
        }
    }
    /**
     * {@inheritdoc}
     */
    protected function createConfigurationDefinition() : FixerConfigurationResolverInterface
    {
        return new FixerConfigurationResolver([(new FixerOptionBuilder('space_before', 'Spacing to apply before colon.'))->setAllowedValues(['one', 'none'])->setDefault('none')->getOption()]);
    }
}
