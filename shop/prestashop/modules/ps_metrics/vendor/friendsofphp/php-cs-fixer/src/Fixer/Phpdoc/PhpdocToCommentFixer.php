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
use ps_metrics_module_v4_0_6\PhpCsFixer\Fixer\ConfigurableFixerInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerConfigurationResolver;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\CodeSample;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinition;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\Preg;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Analyzer\CommentsAnalyzer;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Token;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Tokens;
/**
 * @author Ceeram <ceeram@cakephp.org>
 * @author Dariusz Rumiński <dariusz.ruminski@gmail.com>
 */
final class PhpdocToCommentFixer extends AbstractFixer implements ConfigurableFixerInterface
{
    /**
     * @var string[]
     */
    private $ignoredTags = [];
    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens) : bool
    {
        return $tokens->isTokenKindFound(\T_DOC_COMMENT);
    }
    /**
     * {@inheritdoc}
     *
     * Must run before GeneralPhpdocAnnotationRemoveFixer, GeneralPhpdocTagRenameFixer, NoBlankLinesAfterPhpdocFixer, NoEmptyCommentFixer, NoEmptyPhpdocFixer, NoSuperfluousPhpdocTagsFixer, PhpdocAddMissingParamAnnotationFixer, PhpdocAlignFixer, PhpdocAlignFixer, PhpdocAnnotationWithoutDotFixer, PhpdocIndentFixer, PhpdocInlineTagNormalizerFixer, PhpdocLineSpanFixer, PhpdocNoAccessFixer, PhpdocNoAliasTagFixer, PhpdocNoEmptyReturnFixer, PhpdocNoPackageFixer, PhpdocNoUselessInheritdocFixer, PhpdocNoUselessInheritdocFixer, PhpdocOrderByValueFixer, PhpdocOrderFixer, PhpdocReturnSelfReferenceFixer, PhpdocSeparationFixer, PhpdocSingleLineVarSpacingFixer, PhpdocSummaryFixer, PhpdocTagCasingFixer, PhpdocTagTypeFixer, PhpdocToParamTypeFixer, PhpdocToPropertyTypeFixer, PhpdocToReturnTypeFixer, PhpdocTrimConsecutiveBlankLineSeparationFixer, PhpdocTrimFixer, PhpdocTypesOrderFixer, PhpdocVarAnnotationCorrectOrderFixer, PhpdocVarWithoutNameFixer.
     * Must run after CommentToPhpdocFixer.
     */
    public function getPriority() : int
    {
        /*
         * Should be run before all other docblock fixers so that these fixers
         * don't touch doc comments which are meant to be converted to regular
         * comments.
         */
        return 25;
    }
    /**
     * {@inheritdoc}
     */
    public function getDefinition() : FixerDefinitionInterface
    {
        return new FixerDefinition('Docblocks should only be used on structural elements.', [new CodeSample('<?php
$first = true;// needed because by default first docblock is never fixed.

/** This should be a comment */
foreach($connections as $key => $sqlite) {
    $sqlite->open($path);
}
'), new CodeSample('<?php
$first = true;// needed because by default first docblock is never fixed.

/** This should be a comment */
foreach($connections as $key => $sqlite) {
    $sqlite->open($path);
}

/** @todo This should be a PHPDoc as the tag is on "ignored_tags" list */
foreach($connections as $key => $sqlite) {
    $sqlite->open($path);
}
', ['ignored_tags' => ['todo']])]);
    }
    /**
     * {@inheritdoc}
     */
    public function configure(array $configuration = null) : void
    {
        parent::configure($configuration);
        $this->ignoredTags = \array_map(static function (string $tag) : string {
            return \strtolower($tag);
        }, $this->configuration['ignored_tags']);
    }
    /**
     * {@inheritdoc}
     */
    protected function createConfigurationDefinition() : FixerConfigurationResolverInterface
    {
        return new FixerConfigurationResolver([(new FixerOptionBuilder('ignored_tags', 'List of ignored tags (matched case insensitively)'))->setAllowedTypes(['array'])->setDefault([])->getOption()]);
    }
    /**
     * {@inheritdoc}
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens) : void
    {
        $commentsAnalyzer = new CommentsAnalyzer();
        foreach ($tokens as $index => $token) {
            if (!$token->isGivenKind(\T_DOC_COMMENT)) {
                continue;
            }
            if ($commentsAnalyzer->isHeaderComment($tokens, $index)) {
                continue;
            }
            if ($commentsAnalyzer->isBeforeStructuralElement($tokens, $index)) {
                continue;
            }
            if (0 < Preg::matchAll('~\\@([a-zA-Z0-9_\\\\-]+)\\b~', $token->getContent(), $matches)) {
                foreach ($matches[1] as $match) {
                    if (\in_array(\strtolower($match), $this->ignoredTags, \true)) {
                        continue 2;
                    }
                }
            }
            $tokens[$index] = new Token([\T_COMMENT, '/*' . \ltrim($token->getContent(), '/*')]);
        }
    }
}
