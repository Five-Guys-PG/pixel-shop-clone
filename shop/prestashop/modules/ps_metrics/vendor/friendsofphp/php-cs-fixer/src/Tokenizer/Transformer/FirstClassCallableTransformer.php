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
namespace ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Transformer;

use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\AbstractTransformer;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\CT;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Token;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Tokens;
/**
 * @internal
 */
final class FirstClassCallableTransformer extends AbstractTransformer
{
    /**
     * {@inheritdoc}
     */
    public function getRequiredPhpVersionId() : int
    {
        return 80100;
    }
    /**
     * {@inheritdoc}
     */
    public function process(Tokens $tokens, Token $token, int $index) : void
    {
        if ($token->isGivenKind(\T_ELLIPSIS) && $tokens[$tokens->getPrevMeaningfulToken($index)]->equals('(') && $tokens[$tokens->getNextMeaningfulToken($index)]->equals(')')) {
            $tokens[$index] = new Token([CT::T_FIRST_CLASS_CALLABLE, '...']);
        }
    }
    /**
     * {@inheritdoc}
     */
    public function getCustomTokens() : array
    {
        return [CT::T_FIRST_CLASS_CALLABLE];
    }
}
