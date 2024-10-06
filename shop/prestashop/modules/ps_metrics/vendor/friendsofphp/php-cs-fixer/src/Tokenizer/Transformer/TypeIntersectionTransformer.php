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

use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\AbstractTypeTransformer;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\CT;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Token;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Tokens;
/**
 * Transform `&` operator into CT::T_TYPE_INTERSECTION in `function foo(Type1 & Type2 $x) {`
 * or `} catch (ExceptionType1 & ExceptionType2 $e) {`.
 *
 * @internal
 */
final class TypeIntersectionTransformer extends AbstractTypeTransformer
{
    /**
     * {@inheritdoc}
     */
    public function getPriority() : int
    {
        // needs to run after ArrayTypehintTransformer and TypeColonTransformer
        return -15;
    }
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
        $this->doProcess($tokens, $index, [\T_AMPERSAND_NOT_FOLLOWED_BY_VAR_OR_VARARG, '&']);
    }
    /**
     * {@inheritdoc}
     */
    public function getCustomTokens() : array
    {
        return [CT::T_TYPE_INTERSECTION];
    }
    protected function replaceToken(Tokens $tokens, int $index) : void
    {
        $tokens[$index] = new Token([CT::T_TYPE_INTERSECTION, '&']);
    }
}
