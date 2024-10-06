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
namespace ps_metrics_module_v4_0_6\PhpCsFixer\Fixer\Strict;

use ps_metrics_module_v4_0_6\PhpCsFixer\AbstractFixer;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\CodeSample;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinition;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Token;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Tokens;
/**
 * @author Dariusz Rumiński <dariusz.ruminski@gmail.com>
 */
final class StrictComparisonFixer extends AbstractFixer
{
    public function getDefinition() : FixerDefinitionInterface
    {
        return new FixerDefinition('Comparisons should be strict.', [new CodeSample("<?php\n\$a = 1== \$b;\n")], null, 'Changing comparisons to strict might change code behavior.');
    }
    /**
     * {@inheritdoc}
     *
     * Must run before BinaryOperatorSpacesFixer.
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
        return $tokens->isAnyTokenKindsFound([\T_IS_EQUAL, \T_IS_NOT_EQUAL]);
    }
    /**
     * {@inheritdoc}
     */
    public function isRisky() : bool
    {
        return \true;
    }
    /**
     * {@inheritdoc}
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens) : void
    {
        static $map = [\T_IS_EQUAL => ['id' => \T_IS_IDENTICAL, 'content' => '==='], \T_IS_NOT_EQUAL => ['id' => \T_IS_NOT_IDENTICAL, 'content' => '!==']];
        foreach ($tokens as $index => $token) {
            $tokenId = $token->getId();
            if (isset($map[$tokenId])) {
                $tokens[$index] = new Token([$map[$tokenId]['id'], $map[$tokenId]['content']]);
            }
        }
    }
}
