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
use ps_metrics_module_v4_0_6\PhpCsFixer\Fixer\ConfigurableFixerInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerConfigurationResolver;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\CodeSample;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinition;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\Preg;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Token;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Tokens;
use ps_metrics_module_v4_0_6\PhpCsFixer\Utils;
final class PhpdocTypesOrderFixer extends AbstractFixer implements ConfigurableFixerInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDefinition() : FixerDefinitionInterface
    {
        return new FixerDefinition('Sorts PHPDoc types.', [new CodeSample('<?php
/**
 * @param string|null $bar
 */
'), new CodeSample('<?php
/**
 * @param null|string $bar
 */
', ['null_adjustment' => 'always_last']), new CodeSample('<?php
/**
 * @param null|string|int|\\Foo $bar
 */
', ['sort_algorithm' => 'alpha']), new CodeSample('<?php
/**
 * @param null|string|int|\\Foo $bar
 */
', ['sort_algorithm' => 'alpha', 'null_adjustment' => 'always_last']), new CodeSample('<?php
/**
 * @param null|string|int|\\Foo $bar
 */
', ['sort_algorithm' => 'alpha', 'null_adjustment' => 'none'])]);
    }
    /**
     * {@inheritdoc}
     *
     * Must run before PhpdocAlignFixer.
     * Must run after AlignMultilineCommentFixer, CommentToPhpdocFixer, PhpdocAnnotationWithoutDotFixer, PhpdocIndentFixer, PhpdocScalarFixer, PhpdocToCommentFixer, PhpdocTypesFixer.
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
        return $tokens->isTokenKindFound(\T_DOC_COMMENT);
    }
    /**
     * {@inheritdoc}
     */
    protected function createConfigurationDefinition() : FixerConfigurationResolverInterface
    {
        return new FixerConfigurationResolver([(new FixerOptionBuilder('sort_algorithm', 'The sorting algorithm to apply.'))->setAllowedValues(['alpha', 'none'])->setDefault('alpha')->getOption(), (new FixerOptionBuilder('null_adjustment', 'Forces the position of `null` (overrides `sort_algorithm`).'))->setAllowedValues(['always_first', 'always_last', 'none'])->setDefault('always_first')->getOption()]);
    }
    protected function applyFix(\SplFileInfo $file, Tokens $tokens) : void
    {
        foreach ($tokens as $index => $token) {
            if (!$token->isGivenKind(\T_DOC_COMMENT)) {
                continue;
            }
            $doc = new DocBlock($token->getContent());
            $annotations = $doc->getAnnotationsOfType(Annotation::getTagsWithTypes());
            if (0 === \count($annotations)) {
                continue;
            }
            foreach ($annotations as $annotation) {
                $types = $annotation->getTypes();
                // fix main types
                $annotation->setTypes($this->sortTypes($types));
                // fix @method parameters types
                $line = $doc->getLine($annotation->getStart());
                $line->setContent(Preg::replaceCallback('/(@method\\s+.+?\\s+\\w+\\()(.*)\\)/', function (array $matches) {
                    $sorted = Preg::replaceCallback('/([^\\s,]+)([\\s]+\\$[^\\s,]+)/', function (array $matches) : string {
                        return $this->sortJoinedTypes($matches[1]) . $matches[2];
                    }, $matches[2]);
                    return $matches[1] . $sorted . ')';
                }, $line->getContent()));
            }
            $tokens[$index] = new Token([\T_DOC_COMMENT, $doc->getContent()]);
        }
    }
    /**
     * @param string[] $types
     *
     * @return string[]
     */
    private function sortTypes(array $types) : array
    {
        foreach ($types as $index => $type) {
            $types[$index] = Preg::replaceCallback('/^([^<]+)<(?:([\\w\\|]+?|<?.*>)(,\\s*))?(.*)>$/', function (array $matches) {
                return $matches[1] . '<' . $this->sortJoinedTypes($matches[2]) . $matches[3] . $this->sortJoinedTypes($matches[4]) . '>';
            }, $type);
        }
        if ('alpha' === $this->configuration['sort_algorithm']) {
            $types = Utils::stableSort($types, static function (string $type) : string {
                return $type;
            }, static function (string $typeA, string $typeB) : int {
                $regexp = '/^\\??\\\\?/';
                return \strcasecmp(Preg::replace($regexp, '', $typeA), Preg::replace($regexp, '', $typeB));
            });
        }
        if ('none' !== $this->configuration['null_adjustment']) {
            $nulls = [];
            foreach ($types as $index => $type) {
                if (Preg::match('/^\\\\?null$/i', $type)) {
                    $nulls[$index] = $type;
                    unset($types[$index]);
                }
            }
            if (\count($nulls) > 0) {
                if ('always_last' === $this->configuration['null_adjustment']) {
                    \array_push($types, ...$nulls);
                } else {
                    \array_unshift($types, ...$nulls);
                }
            }
        }
        return $types;
    }
    private function sortJoinedTypes(string $types) : string
    {
        $types = \array_filter(Preg::split('/([^|<{\\(]+(?:[<{].*[>}]|\\(.+\\)(?::.+)?)?)/', $types, -1, \PREG_SPLIT_DELIM_CAPTURE | \PREG_SPLIT_NO_EMPTY), static function (string $value) : bool {
            return '|' !== $value;
        });
        return \implode('|', $this->sortTypes($types));
    }
}
