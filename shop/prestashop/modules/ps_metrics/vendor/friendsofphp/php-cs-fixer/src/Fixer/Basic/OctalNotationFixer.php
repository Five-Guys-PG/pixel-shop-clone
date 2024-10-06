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
namespace ps_metrics_module_v4_0_6\PhpCsFixer\Fixer\Basic;

use ps_metrics_module_v4_0_6\PhpCsFixer\AbstractFixer;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinition;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\VersionSpecification;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\VersionSpecificCodeSample;
use ps_metrics_module_v4_0_6\PhpCsFixer\Preg;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Token;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Tokens;
final class OctalNotationFixer extends AbstractFixer
{
    /**
     * {@inheritdoc}
     */
    public function getDefinition() : FixerDefinitionInterface
    {
        return new FixerDefinition('Literal octal must be in `0o` notation.', [new VersionSpecificCodeSample("<?php \$foo = 0123;\n", new VersionSpecification(80100))]);
    }
    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens) : bool
    {
        return \PHP_VERSION_ID >= 80100 && $tokens->isTokenKindFound(\T_LNUMBER);
    }
    /**
     * {@inheritdoc}
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens) : void
    {
        foreach ($tokens as $index => $token) {
            if (!$token->isGivenKind(\T_LNUMBER)) {
                continue;
            }
            $content = $token->getContent();
            if (1 !== Preg::match('#^0\\d+$#', $content)) {
                continue;
            }
            $tokens[$index] = 1 === Preg::match('#^0+$#', $content) ? new Token([\T_LNUMBER, '0']) : new Token([\T_LNUMBER, '0o' . \substr($content, 1)]);
        }
    }
}
