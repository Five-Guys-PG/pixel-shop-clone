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

use ps_metrics_module_v4_0_6\PhpCsFixer\AbstractFopenFlagFixer;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\CodeSample;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinition;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\Preg;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Token;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Tokens;
final class FopenFlagOrderFixer extends AbstractFopenFlagFixer
{
    /**
     * {@inheritdoc}
     */
    public function getDefinition() : FixerDefinitionInterface
    {
        return new FixerDefinition('Order the flags in `fopen` calls, `b` and `t` must be last.', [new CodeSample("<?php\n\$a = fopen(\$foo, 'br+');\n")], null, 'Risky when the function `fopen` is overridden.');
    }
    protected function fixFopenFlagToken(Tokens $tokens, int $argumentStartIndex, int $argumentEndIndex) : void
    {
        $argumentFlagIndex = null;
        for ($i = $argumentStartIndex; $i <= $argumentEndIndex; ++$i) {
            if ($tokens[$i]->isGivenKind([\T_WHITESPACE, \T_COMMENT, \T_DOC_COMMENT])) {
                continue;
            }
            if (null !== $argumentFlagIndex) {
                return;
                // multiple meaningful tokens found, no candidate for fixing
            }
            $argumentFlagIndex = $i;
        }
        // check if second argument is candidate
        if (null === $argumentFlagIndex || !$tokens[$argumentFlagIndex]->isGivenKind(\T_CONSTANT_ENCAPSED_STRING)) {
            return;
        }
        $content = $tokens[$argumentFlagIndex]->getContent();
        $contentQuote = $content[0];
        // `'`, `"`, `b` or `B`
        if ('b' === $contentQuote || 'B' === $contentQuote) {
            $binPrefix = $contentQuote;
            $contentQuote = $content[1];
            // `'` or `"`
            $mode = \substr($content, 2, -1);
        } else {
            $binPrefix = '';
            $mode = \substr($content, 1, -1);
        }
        $modeLength = \strlen($mode);
        if ($modeLength < 2) {
            return;
            // nothing to sort
        }
        if (\false === $this->isValidModeString($mode)) {
            return;
        }
        $split = $this->sortFlags(Preg::split('#([^\\+]\\+?)#', $mode, -1, \PREG_SPLIT_NO_EMPTY | \PREG_SPLIT_DELIM_CAPTURE));
        $newContent = $binPrefix . $contentQuote . \implode('', $split) . $contentQuote;
        if ($content !== $newContent) {
            $tokens[$argumentFlagIndex] = new Token([\T_CONSTANT_ENCAPSED_STRING, $newContent]);
        }
    }
    /**
     * @param string[] $flags
     *
     * @return string[]
     */
    private function sortFlags(array $flags) : array
    {
        \usort($flags, static function (string $flag1, string $flag2) : int {
            if ($flag1 === $flag2) {
                return 0;
            }
            if ('b' === $flag1) {
                return 1;
            }
            if ('b' === $flag2) {
                return -1;
            }
            if ('t' === $flag1) {
                return 1;
            }
            if ('t' === $flag2) {
                return -1;
            }
            return $flag1 < $flag2 ? -1 : 1;
        });
        return $flags;
    }
}
