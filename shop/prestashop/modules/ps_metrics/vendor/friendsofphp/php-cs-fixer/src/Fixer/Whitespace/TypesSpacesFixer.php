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
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerConfigurationResolver;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\CodeSample;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinition;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\VersionSpecification;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\VersionSpecificCodeSample;
use ps_metrics_module_v4_0_6\PhpCsFixer\Preg;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\CT;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Token;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Tokens;
final class TypesSpacesFixer extends AbstractFixer implements ConfigurableFixerInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDefinition() : FixerDefinitionInterface
    {
        return new FixerDefinition('A single space or none should be around union type operator.', [new CodeSample("<?php\ntry\n{\n    new Foo();\n} catch (ErrorA | ErrorB \$e) {\necho'error';}\n"), new CodeSample("<?php\ntry\n{\n    new Foo();\n} catch (ErrorA|ErrorB \$e) {\necho'error';}\n", ['space' => 'single']), new VersionSpecificCodeSample("<?php\nfunction foo(int | string \$x)\n{\n}\n", new VersionSpecification(80000))]);
    }
    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens) : bool
    {
        return $tokens->isAnyTokenKindsFound([CT::T_TYPE_ALTERNATION, CT::T_TYPE_INTERSECTION]);
    }
    /**
     * {@inheritdoc}
     */
    protected function createConfigurationDefinition() : FixerConfigurationResolverInterface
    {
        return new FixerConfigurationResolver([(new FixerOptionBuilder('space', 'spacing to apply around union type operator.'))->setAllowedValues(['none', 'single'])->setDefault('none')->getOption()]);
    }
    protected function applyFix(\SplFileInfo $file, Tokens $tokens) : void
    {
        for ($index = $tokens->count() - 1; $index > 0; --$index) {
            if (!$tokens[$index]->isGivenKind([CT::T_TYPE_ALTERNATION, CT::T_TYPE_INTERSECTION])) {
                continue;
            }
            if ('single' === $this->configuration['space']) {
                $this->ensureSingleSpace($tokens, $index + 1, 0);
                $this->ensureSingleSpace($tokens, $index - 1, 1);
            } else {
                $this->ensureNoSpace($tokens, $index + 1);
                $this->ensureNoSpace($tokens, $index - 1);
            }
        }
    }
    private function ensureSingleSpace(Tokens $tokens, int $index, int $offset) : void
    {
        if (!$tokens[$index]->isWhitespace()) {
            $tokens->insertSlices([$index + $offset => new Token([\T_WHITESPACE, ' '])]);
            return;
        }
        if (' ' === $tokens[$index]->getContent()) {
            return;
        }
        if (1 === Preg::match('/\\R/', $tokens[$index]->getContent())) {
            return;
        }
        $tokens[$index] = new Token([\T_WHITESPACE, ' ']);
    }
    private function ensureNoSpace(Tokens $tokens, int $index) : void
    {
        if (!$tokens[$index]->isWhitespace()) {
            return;
        }
        if (1 === Preg::match('/\\R/', $tokens[$index]->getContent())) {
            return;
        }
        $tokens->clearAt($index);
    }
}
