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
namespace ps_metrics_module_v4_0_6\PhpCsFixer\Fixer\NamespaceNotation;

use ps_metrics_module_v4_0_6\PhpCsFixer\AbstractLinesBeforeNamespaceFixer;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\CodeSample;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinition;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Tokens;
/**
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
final class NoBlankLinesBeforeNamespaceFixer extends AbstractLinesBeforeNamespaceFixer
{
    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens) : bool
    {
        return $tokens->isTokenKindFound(\T_NAMESPACE);
    }
    /**
     * {@inheritdoc}
     */
    public function getDefinition() : FixerDefinitionInterface
    {
        return new FixerDefinition('There should be no blank lines before a namespace declaration.', [new CodeSample("<?php\n\n\n\nnamespace Example;\n")]);
    }
    /**
     * {@inheritdoc}
     *
     * Must run after BlankLineAfterOpeningTagFixer.
     */
    public function getPriority() : int
    {
        return 0;
    }
    /**
     * {@inheritdoc}
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens) : void
    {
        for ($index = 0, $limit = $tokens->count(); $index < $limit; ++$index) {
            $token = $tokens[$index];
            if (!$token->isGivenKind(\T_NAMESPACE)) {
                continue;
            }
            $this->fixLinesBeforeNamespace($tokens, $index, 0, 1);
        }
    }
}
