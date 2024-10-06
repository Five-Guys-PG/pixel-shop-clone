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
use ps_metrics_module_v4_0_6\PhpCsFixer\DocBlock\DocBlock;
use ps_metrics_module_v4_0_6\PhpCsFixer\DocBlock\Line;
use ps_metrics_module_v4_0_6\PhpCsFixer\Fixer\ConfigurableFixerInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\Fixer\WhitespacesAwareFixerInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerConfigurationResolver;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\CodeSample;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinition;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\Preg;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Analyzer\ArgumentsAnalyzer;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Token;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Tokens;
/**
 * @author Dariusz Rumiński <dariusz.ruminski@gmail.com>
 */
final class PhpdocAddMissingParamAnnotationFixer extends AbstractFixer implements ConfigurableFixerInterface, WhitespacesAwareFixerInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDefinition() : FixerDefinitionInterface
    {
        return new FixerDefinition('PHPDoc should contain `@param` for all params.', [new CodeSample('<?php
/**
 * @param int $bar
 *
 * @return void
 */
function f9(string $foo, $bar, $baz) {}
'), new CodeSample('<?php
/**
 * @param int $bar
 *
 * @return void
 */
function f9(string $foo, $bar, $baz) {}
', ['only_untyped' => \true]), new CodeSample('<?php
/**
 * @param int $bar
 *
 * @return void
 */
function f9(string $foo, $bar, $baz) {}
', ['only_untyped' => \false])]);
    }
    /**
     * {@inheritdoc}
     *
     * Must run before NoEmptyPhpdocFixer, NoSuperfluousPhpdocTagsFixer, PhpdocAlignFixer, PhpdocAlignFixer, PhpdocOrderFixer.
     * Must run after AlignMultilineCommentFixer, CommentToPhpdocFixer, GeneralPhpdocTagRenameFixer, PhpdocIndentFixer, PhpdocNoAliasTagFixer, PhpdocScalarFixer, PhpdocToCommentFixer, PhpdocTypesFixer.
     */
    public function getPriority() : int
    {
        return 10;
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
    protected function applyFix(\SplFileInfo $file, Tokens $tokens) : void
    {
        $argumentsAnalyzer = new ArgumentsAnalyzer();
        for ($index = 0, $limit = $tokens->count(); $index < $limit; ++$index) {
            $token = $tokens[$index];
            if (!$token->isGivenKind(\T_DOC_COMMENT)) {
                continue;
            }
            $tokenContent = $token->getContent();
            if (\false !== \stripos($tokenContent, 'inheritdoc')) {
                continue;
            }
            // ignore one-line phpdocs like `/** foo */`, as there is no place to put new annotations
            if (!\str_contains($tokenContent, "\n")) {
                continue;
            }
            $mainIndex = $index;
            $index = $tokens->getNextMeaningfulToken($index);
            if (null === $index) {
                return;
            }
            while ($tokens[$index]->isGivenKind([\T_ABSTRACT, \T_FINAL, \T_PRIVATE, \T_PROTECTED, \T_PUBLIC, \T_STATIC, \T_VAR])) {
                $index = $tokens->getNextMeaningfulToken($index);
            }
            if (!$tokens[$index]->isGivenKind(\T_FUNCTION)) {
                continue;
            }
            $openIndex = $tokens->getNextTokenOfKind($index, ['(']);
            $index = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_PARENTHESIS_BRACE, $openIndex);
            $arguments = [];
            foreach ($argumentsAnalyzer->getArguments($tokens, $openIndex, $index) as $start => $end) {
                $argumentInfo = $this->prepareArgumentInformation($tokens, $start, $end);
                if (\false === $this->configuration['only_untyped'] || '' === $argumentInfo['type']) {
                    $arguments[$argumentInfo['name']] = $argumentInfo;
                }
            }
            if (0 === \count($arguments)) {
                continue;
            }
            $doc = new DocBlock($tokenContent);
            $lastParamLine = null;
            foreach ($doc->getAnnotationsOfType('param') as $annotation) {
                $pregMatched = Preg::match('/^[^$]+(\\$\\w+).*$/s', $annotation->getContent(), $matches);
                if (1 === $pregMatched) {
                    unset($arguments[$matches[1]]);
                }
                $lastParamLine = \max($lastParamLine, $annotation->getEnd());
            }
            if (0 === \count($arguments)) {
                continue;
            }
            $lines = $doc->getLines();
            $linesCount = \count($lines);
            Preg::match('/^(\\s*).*$/', $lines[$linesCount - 1]->getContent(), $matches);
            $indent = $matches[1];
            $newLines = [];
            foreach ($arguments as $argument) {
                $type = $argument['type'] ?: 'mixed';
                if (!\str_starts_with($type, '?') && 'null' === \strtolower($argument['default'])) {
                    $type = 'null|' . $type;
                }
                $newLines[] = new Line(\sprintf('%s* @param %s %s%s', $indent, $type, $argument['name'], $this->whitespacesConfig->getLineEnding()));
            }
            \array_splice($lines, $lastParamLine ? $lastParamLine + 1 : $linesCount - 1, 0, $newLines);
            $tokens[$mainIndex] = new Token([\T_DOC_COMMENT, \implode('', $lines)]);
        }
    }
    /**
     * {@inheritdoc}
     */
    protected function createConfigurationDefinition() : FixerConfigurationResolverInterface
    {
        return new FixerConfigurationResolver([(new FixerOptionBuilder('only_untyped', 'Whether to add missing `@param` annotations for untyped parameters only.'))->setDefault(\true)->setAllowedTypes(['bool'])->getOption()]);
    }
    private function prepareArgumentInformation(Tokens $tokens, int $start, int $end) : array
    {
        $info = ['default' => '', 'name' => '', 'type' => ''];
        $sawName = \false;
        for ($index = $start; $index <= $end; ++$index) {
            $token = $tokens[$index];
            if ($token->isComment() || $token->isWhitespace()) {
                continue;
            }
            if ($token->isGivenKind(\T_VARIABLE)) {
                $sawName = \true;
                $info['name'] = $token->getContent();
                continue;
            }
            if ($token->equals('=')) {
                continue;
            }
            if ($sawName) {
                $info['default'] .= $token->getContent();
            } elseif (!$token->equals('&')) {
                if ($token->isGivenKind(\T_ELLIPSIS)) {
                    if ('' === $info['type']) {
                        $info['type'] = 'array';
                    } else {
                        $info['type'] .= '[]';
                    }
                } else {
                    $info['type'] .= $token->getContent();
                }
            }
        }
        return $info;
    }
}
