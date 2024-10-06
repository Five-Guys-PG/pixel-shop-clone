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

use ps_metrics_module_v4_0_6\PhpCsFixer\DocBlock\DocBlock;
use ps_metrics_module_v4_0_6\PhpCsFixer\DocBlock\Line;
use ps_metrics_module_v4_0_6\PhpCsFixer\Fixer\AbstractPhpUnitFixer;
use ps_metrics_module_v4_0_6\PhpCsFixer\Fixer\ConfigurableFixerInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\Fixer\WhitespacesAwareFixerInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\AllowedValueSubset;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerConfigurationResolver;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\CodeSample;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinition;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Analyzer\WhitespacesAnalyzer;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Token;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Tokens;
/**
 * @author Gert de Pagter <BackEndTea@gmail.com>
 */
final class PhpUnitInternalClassFixer extends AbstractPhpUnitFixer implements WhitespacesAwareFixerInterface, ConfigurableFixerInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDefinition() : FixerDefinitionInterface
    {
        return new FixerDefinition('All PHPUnit test classes should be marked as internal.', [new CodeSample("<?php\nclass MyTest extends TestCase {}\n"), new CodeSample("<?php\nclass MyTest extends TestCase {}\nfinal class FinalTest extends TestCase {}\nabstract class AbstractTest extends TestCase {}\n", ['types' => ['final']])]);
    }
    /**
     * {@inheritdoc}
     *
     * Must run before FinalInternalClassFixer.
     */
    public function getPriority() : int
    {
        return 68;
    }
    /**
     * {@inheritdoc}
     */
    protected function createConfigurationDefinition() : FixerConfigurationResolverInterface
    {
        $types = ['normal', 'final', 'abstract'];
        return new FixerConfigurationResolver([(new FixerOptionBuilder('types', 'What types of classes to mark as internal'))->setAllowedValues([new AllowedValueSubset($types)])->setAllowedTypes(['array'])->setDefault(['normal', 'final'])->getOption()]);
    }
    /**
     * {@inheritdoc}
     */
    protected function applyPhpUnitClassFix(Tokens $tokens, int $startIndex, int $endIndex) : void
    {
        $classIndex = $tokens->getPrevTokenOfKind($startIndex, [[\T_CLASS]]);
        if (!$this->isAllowedByConfiguration($tokens, $classIndex)) {
            return;
        }
        $docBlockIndex = $this->getDocBlockIndex($tokens, $classIndex);
        if ($this->isPHPDoc($tokens, $docBlockIndex)) {
            $this->updateDocBlockIfNeeded($tokens, $docBlockIndex);
        } else {
            $this->createDocBlock($tokens, $docBlockIndex);
        }
    }
    private function isAllowedByConfiguration(Tokens $tokens, int $i) : bool
    {
        $typeIndex = $tokens->getPrevMeaningfulToken($i);
        if ($tokens[$typeIndex]->isGivenKind(\T_FINAL)) {
            return \in_array('final', $this->configuration['types'], \true);
        }
        if ($tokens[$typeIndex]->isGivenKind(\T_ABSTRACT)) {
            return \in_array('abstract', $this->configuration['types'], \true);
        }
        return \in_array('normal', $this->configuration['types'], \true);
    }
    private function createDocBlock(Tokens $tokens, int $docBlockIndex) : void
    {
        $lineEnd = $this->whitespacesConfig->getLineEnding();
        $originalIndent = WhitespacesAnalyzer::detectIndent($tokens, $tokens->getNextNonWhitespace($docBlockIndex));
        $toInsert = [new Token([\T_DOC_COMMENT, '/**' . $lineEnd . "{$originalIndent} * @internal" . $lineEnd . "{$originalIndent} */"]), new Token([\T_WHITESPACE, $lineEnd . $originalIndent])];
        $index = $tokens->getNextMeaningfulToken($docBlockIndex);
        $tokens->insertAt($index, $toInsert);
    }
    private function updateDocBlockIfNeeded(Tokens $tokens, int $docBlockIndex) : void
    {
        $doc = new DocBlock($tokens[$docBlockIndex]->getContent());
        if (!empty($doc->getAnnotationsOfType('internal'))) {
            return;
        }
        $doc = $this->makeDocBlockMultiLineIfNeeded($doc, $tokens, $docBlockIndex);
        $lines = $this->addInternalAnnotation($doc, $tokens, $docBlockIndex);
        $lines = \implode('', $lines);
        $tokens[$docBlockIndex] = new Token([\T_DOC_COMMENT, $lines]);
    }
    /**
     * @return Line[]
     */
    private function addInternalAnnotation(DocBlock $docBlock, Tokens $tokens, int $docBlockIndex) : array
    {
        $lines = $docBlock->getLines();
        $originalIndent = WhitespacesAnalyzer::detectIndent($tokens, $docBlockIndex);
        $lineEnd = $this->whitespacesConfig->getLineEnding();
        \array_splice($lines, -1, 0, $originalIndent . ' *' . $lineEnd . $originalIndent . ' * @internal' . $lineEnd);
        return $lines;
    }
    private function makeDocBlockMultiLineIfNeeded(DocBlock $doc, Tokens $tokens, int $docBlockIndex) : DocBlock
    {
        $lines = $doc->getLines();
        if (1 === \count($lines) && empty($doc->getAnnotationsOfType('internal'))) {
            $indent = WhitespacesAnalyzer::detectIndent($tokens, $tokens->getNextNonWhitespace($docBlockIndex));
            $doc->makeMultiLine($indent, $this->whitespacesConfig->getLineEnding());
            return $doc;
        }
        return $doc;
    }
}
