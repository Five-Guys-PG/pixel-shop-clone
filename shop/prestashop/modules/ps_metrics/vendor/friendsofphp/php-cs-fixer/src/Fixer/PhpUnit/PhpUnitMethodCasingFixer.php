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
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerConfigurationResolver;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\CodeSample;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinition;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\Preg;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Token;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Tokens;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\TokensAnalyzer;
use ps_metrics_module_v4_0_6\PhpCsFixer\Utils;
/**
 * @author Filippo Tessarotto <zoeslam@gmail.com>
 */
final class PhpUnitMethodCasingFixer extends AbstractPhpUnitFixer implements ConfigurableFixerInterface
{
    /**
     * @internal
     */
    public const CAMEL_CASE = 'camel_case';
    /**
     * @internal
     */
    public const SNAKE_CASE = 'snake_case';
    /**
     * {@inheritdoc}
     */
    public function getDefinition() : FixerDefinitionInterface
    {
        return new FixerDefinition('Enforce camel (or snake) case for PHPUnit test methods, following configuration.', [new CodeSample('<?php
class MyTest extends \\PhpUnit\\FrameWork\\TestCase
{
    public function test_my_code() {}
}
'), new CodeSample('<?php
class MyTest extends \\PhpUnit\\FrameWork\\TestCase
{
    public function testMyCode() {}
}
', ['case' => self::SNAKE_CASE])]);
    }
    /**
     * {@inheritdoc}
     *
     * Must run after PhpUnitTestAnnotationFixer.
     */
    public function getPriority() : int
    {
        return 0;
    }
    /**
     * {@inheritdoc}
     */
    protected function createConfigurationDefinition() : FixerConfigurationResolverInterface
    {
        return new FixerConfigurationResolver([(new FixerOptionBuilder('case', 'Apply camel or snake case to test methods'))->setAllowedValues([self::CAMEL_CASE, self::SNAKE_CASE])->setDefault(self::CAMEL_CASE)->getOption()]);
    }
    /**
     * {@inheritdoc}
     */
    protected function applyPhpUnitClassFix(Tokens $tokens, int $startIndex, int $endIndex) : void
    {
        for ($index = $endIndex - 1; $index > $startIndex; --$index) {
            if (!$this->isTestMethod($tokens, $index)) {
                continue;
            }
            $functionNameIndex = $tokens->getNextMeaningfulToken($index);
            $functionName = $tokens[$functionNameIndex]->getContent();
            $newFunctionName = $this->updateMethodCasing($functionName);
            if ($newFunctionName !== $functionName) {
                $tokens[$functionNameIndex] = new Token([\T_STRING, $newFunctionName]);
            }
            $docBlockIndex = $this->getDocBlockIndex($tokens, $index);
            if ($this->isPHPDoc($tokens, $docBlockIndex)) {
                $this->updateDocBlock($tokens, $docBlockIndex);
            }
        }
    }
    private function updateMethodCasing(string $functionName) : string
    {
        $parts = \explode('::', $functionName);
        $functionNamePart = \array_pop($parts);
        if (self::CAMEL_CASE === $this->configuration['case']) {
            $newFunctionNamePart = $functionNamePart;
            $newFunctionNamePart = \ucwords($newFunctionNamePart, '_');
            $newFunctionNamePart = \str_replace('_', '', $newFunctionNamePart);
            $newFunctionNamePart = \lcfirst($newFunctionNamePart);
        } else {
            $newFunctionNamePart = Utils::camelCaseToUnderscore($functionNamePart);
        }
        $parts[] = $newFunctionNamePart;
        return \implode('::', $parts);
    }
    private function isTestMethod(Tokens $tokens, int $index) : bool
    {
        // Check if we are dealing with a (non-abstract, non-lambda) function
        if (!$this->isMethod($tokens, $index)) {
            return \false;
        }
        // if the function name starts with test it's a test
        $functionNameIndex = $tokens->getNextMeaningfulToken($index);
        $functionName = $tokens[$functionNameIndex]->getContent();
        if (\str_starts_with($functionName, 'test')) {
            return \true;
        }
        $docBlockIndex = $this->getDocBlockIndex($tokens, $index);
        return $this->isPHPDoc($tokens, $docBlockIndex) && \str_contains($tokens[$docBlockIndex]->getContent(), '@test');
    }
    private function isMethod(Tokens $tokens, int $index) : bool
    {
        $tokensAnalyzer = new TokensAnalyzer($tokens);
        return $tokens[$index]->isGivenKind(\T_FUNCTION) && !$tokensAnalyzer->isLambda($index);
    }
    private function updateDocBlock(Tokens $tokens, int $docBlockIndex) : void
    {
        $doc = new DocBlock($tokens[$docBlockIndex]->getContent());
        $lines = $doc->getLines();
        $docBlockNeedsUpdate = \false;
        for ($inc = 0; $inc < \count($lines); ++$inc) {
            $lineContent = $lines[$inc]->getContent();
            if (!\str_contains($lineContent, '@depends')) {
                continue;
            }
            $newLineContent = Preg::replaceCallback('/(@depends\\s+)(.+)(\\b)/', function (array $matches) : string {
                return \sprintf('%s%s%s', $matches[1], $this->updateMethodCasing($matches[2]), $matches[3]);
            }, $lineContent);
            if ($newLineContent !== $lineContent) {
                $lines[$inc] = new Line($newLineContent);
                $docBlockNeedsUpdate = \true;
            }
        }
        if ($docBlockNeedsUpdate) {
            $lines = \implode('', $lines);
            $tokens[$docBlockIndex] = new Token([\T_DOC_COMMENT, $lines]);
        }
    }
}
