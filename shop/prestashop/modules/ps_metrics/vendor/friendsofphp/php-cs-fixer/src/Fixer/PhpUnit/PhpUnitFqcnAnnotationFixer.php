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
namespace ps_metrics_module_v4_0_6\PhpCsFixer\Fixer\PhpUnit;

use ps_metrics_module_v4_0_6\PhpCsFixer\Fixer\AbstractPhpUnitFixer;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\CodeSample;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinition;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\Preg;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Token;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Tokens;
/**
 * @author Roland Franssen <franssen.roland@gmail.com>
 */
final class PhpUnitFqcnAnnotationFixer extends AbstractPhpUnitFixer
{
    /**
     * {@inheritdoc}
     */
    public function getDefinition() : FixerDefinitionInterface
    {
        return new FixerDefinition('PHPUnit annotations should be a FQCNs including a root namespace.', [new CodeSample('<?php
final class MyTest extends \\PHPUnit_Framework_TestCase
{
    /**
     * @expectedException InvalidArgumentException
     * @covers Project\\NameSpace\\Something
     * @coversDefaultClass Project\\Default
     * @uses Project\\Test\\Util
     */
    public function testSomeTest()
    {
    }
}
')]);
    }
    /**
     * {@inheritdoc}
     *
     * Must run before NoUnusedImportsFixer, PhpdocOrderByValueFixer.
     */
    public function getPriority() : int
    {
        return -9;
    }
    /**
     * {@inheritdoc}
     */
    protected function applyPhpUnitClassFix(Tokens $tokens, int $startIndex, int $endIndex) : void
    {
        $prevDocCommentIndex = $tokens->getPrevTokenOfKind($startIndex, [[\T_DOC_COMMENT]]);
        if (null !== $prevDocCommentIndex) {
            $startIndex = $prevDocCommentIndex;
        }
        $this->fixPhpUnitClass($tokens, $startIndex, $endIndex);
    }
    private function fixPhpUnitClass(Tokens $tokens, int $startIndex, int $endIndex) : void
    {
        for ($index = $startIndex; $index < $endIndex; ++$index) {
            if ($tokens[$index]->isGivenKind(\T_DOC_COMMENT)) {
                $tokens[$index] = new Token([\T_DOC_COMMENT, Preg::replace('~^(\\s*\\*\\s*@(?:expectedException|covers|coversDefaultClass|uses)\\h+)(?!(?:self|static)::)(\\w.*)$~m', '$1\\\\$2', $tokens[$index]->getContent())]);
            }
        }
    }
}
