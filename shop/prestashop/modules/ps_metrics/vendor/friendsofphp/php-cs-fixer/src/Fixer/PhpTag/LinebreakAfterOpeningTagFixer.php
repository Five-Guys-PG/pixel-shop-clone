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
namespace ps_metrics_module_v4_0_6\PhpCsFixer\Fixer\PhpTag;

use ps_metrics_module_v4_0_6\PhpCsFixer\AbstractFixer;
use ps_metrics_module_v4_0_6\PhpCsFixer\Fixer\WhitespacesAwareFixerInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\CodeSample;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinition;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Token;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Tokens;
/**
 * @author Ceeram <ceeram@cakephp.org>
 */
final class LinebreakAfterOpeningTagFixer extends AbstractFixer implements WhitespacesAwareFixerInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDefinition() : FixerDefinitionInterface
    {
        return new FixerDefinition('Ensure there is no code on the same line as the PHP open tag.', [new CodeSample("<?php \$a = 1;\n\$b = 3;\n")]);
    }
    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens) : bool
    {
        return $tokens->isTokenKindFound(\T_OPEN_TAG);
    }
    /**
     * {@inheritdoc}
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens) : void
    {
        // ignore files with short open tag and ignore non-monolithic files
        if (!$tokens[0]->isGivenKind(\T_OPEN_TAG) || !$tokens->isMonolithicPhp()) {
            return;
        }
        // ignore if linebreak already present
        if (\str_contains($tokens[0]->getContent(), "\n")) {
            return;
        }
        $newlineFound = \false;
        foreach ($tokens as $token) {
            if ($token->isWhitespace() && \str_contains($token->getContent(), "\n")) {
                $newlineFound = \true;
                break;
            }
        }
        // ignore one-line files
        if (!$newlineFound) {
            return;
        }
        $tokens[0] = new Token([\T_OPEN_TAG, \rtrim($tokens[0]->getContent()) . $this->whitespacesConfig->getLineEnding()]);
    }
}
