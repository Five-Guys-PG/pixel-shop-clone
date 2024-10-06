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
namespace ps_metrics_module_v4_0_6\PhpCsFixer\Fixer\Phpdoc;

use ps_metrics_module_v4_0_6\PhpCsFixer\AbstractFixer;
use ps_metrics_module_v4_0_6\PhpCsFixer\DocBlock\Annotation;
use ps_metrics_module_v4_0_6\PhpCsFixer\DocBlock\DocBlock;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\CodeSample;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinition;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Token;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Tokens;
/**
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
final class PhpdocNoEmptyReturnFixer extends AbstractFixer
{
    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens) : bool
    {
        return $tokens->isTokenKindFound(\T_DOC_COMMENT);
    }
    /**
     * {@inheritdoc}
     */
    public function getDefinition() : FixerDefinitionInterface
    {
        return new FixerDefinition('`@return void` and `@return null` annotations should be omitted from PHPDoc.', [new CodeSample('<?php
/**
 * @return null
*/
function foo() {}
'), new CodeSample('<?php
/**
 * @return void
*/
function foo() {}
')]);
    }
    /**
     * {@inheritdoc}
     *
     * Must run before NoEmptyPhpdocFixer, PhpdocAlignFixer, PhpdocOrderFixer, PhpdocSeparationFixer, PhpdocTrimFixer.
     * Must run after AlignMultilineCommentFixer, CommentToPhpdocFixer, PhpdocIndentFixer, PhpdocScalarFixer, PhpdocToCommentFixer, PhpdocTypesFixer, VoidReturnFixer.
     */
    public function getPriority() : int
    {
        return 4;
    }
    /**
     * {@inheritdoc}
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens) : void
    {
        foreach ($tokens as $index => $token) {
            if (!$token->isGivenKind(\T_DOC_COMMENT)) {
                continue;
            }
            $doc = new DocBlock($token->getContent());
            $annotations = $doc->getAnnotationsOfType('return');
            if (0 === \count($annotations)) {
                continue;
            }
            foreach ($annotations as $annotation) {
                $this->fixAnnotation($annotation);
            }
            $newContent = $doc->getContent();
            if ($newContent === $token->getContent()) {
                continue;
            }
            if ('' === $newContent) {
                $tokens->clearTokenAndMergeSurroundingWhitespace($index);
                continue;
            }
            $tokens[$index] = new Token([\T_DOC_COMMENT, $doc->getContent()]);
        }
    }
    /**
     * Remove `return void` or `return null` annotations.
     */
    private function fixAnnotation(Annotation $annotation) : void
    {
        $types = $annotation->getNormalizedTypes();
        if (1 === \count($types) && ('null' === $types[0] || 'void' === $types[0])) {
            $annotation->remove();
        }
    }
}
