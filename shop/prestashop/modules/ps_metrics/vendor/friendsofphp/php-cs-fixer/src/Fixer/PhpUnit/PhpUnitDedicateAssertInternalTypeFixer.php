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
use ps_metrics_module_v4_0_6\PhpCsFixer\Fixer\ConfigurableFixerInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerConfigurationResolver;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\CodeSample;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinition;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Token;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Tokens;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\TokensAnalyzer;
/**
 * @author Filippo Tessarotto <zoeslam@gmail.com>
 */
final class PhpUnitDedicateAssertInternalTypeFixer extends AbstractPhpUnitFixer implements ConfigurableFixerInterface
{
    /**
     * @var array
     */
    private $typeToDedicatedAssertMap = ['array' => 'assertIsArray', 'boolean' => 'assertIsBool', 'bool' => 'assertIsBool', 'double' => 'assertIsFloat', 'float' => 'assertIsFloat', 'integer' => 'assertIsInt', 'int' => 'assertIsInt', 'null' => 'assertNull', 'numeric' => 'assertIsNumeric', 'object' => 'assertIsObject', 'real' => 'assertIsFloat', 'resource' => 'assertIsResource', 'string' => 'assertIsString', 'scalar' => 'assertIsScalar', 'callable' => 'assertIsCallable', 'iterable' => 'assertIsIterable'];
    /**
     * {@inheritdoc}
     */
    public function getDefinition() : FixerDefinitionInterface
    {
        return new FixerDefinition('PHPUnit assertions like `assertIsArray` should be used over `assertInternalType`.', [new CodeSample('<?php
final class MyTest extends \\PHPUnit\\Framework\\TestCase
{
    public function testMe()
    {
        $this->assertInternalType("array", $var);
        $this->assertInternalType("boolean", $var);
    }
}
'), new CodeSample('<?php
final class MyTest extends \\PHPUnit\\Framework\\TestCase
{
    public function testMe()
    {
        $this->assertInternalType("array", $var);
        $this->assertInternalType("boolean", $var);
    }
}
', ['target' => PhpUnitTargetVersion::VERSION_7_5])], null, 'Risky when PHPUnit methods are overridden or when project has PHPUnit incompatibilities.');
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
     *
     * Must run after PhpUnitDedicateAssertFixer.
     */
    public function getPriority() : int
    {
        return -16;
    }
    /**
     * {@inheritdoc}
     */
    protected function createConfigurationDefinition() : FixerConfigurationResolverInterface
    {
        return new FixerConfigurationResolver([(new FixerOptionBuilder('target', 'Target version of PHPUnit.'))->setAllowedTypes(['string'])->setAllowedValues([PhpUnitTargetVersion::VERSION_7_5, PhpUnitTargetVersion::VERSION_NEWEST])->setDefault(PhpUnitTargetVersion::VERSION_NEWEST)->getOption()]);
    }
    /**
     * {@inheritdoc}
     */
    protected function applyPhpUnitClassFix(Tokens $tokens, int $startIndex, int $endIndex) : void
    {
        $anonymousClassIndexes = [];
        $tokenAnalyzer = new TokensAnalyzer($tokens);
        for ($index = $startIndex; $index < $endIndex; ++$index) {
            if (!$tokens[$index]->isClassy() || !$tokenAnalyzer->isAnonymousClass($index)) {
                continue;
            }
            $openingBraceIndex = $tokens->getNextTokenOfKind($index, ['{']);
            $closingBraceIndex = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_CURLY_BRACE, $openingBraceIndex);
            $anonymousClassIndexes[$closingBraceIndex] = $openingBraceIndex;
        }
        for ($index = $endIndex - 1; $index > $startIndex; --$index) {
            if (isset($anonymousClassIndexes[$index])) {
                $index = $anonymousClassIndexes[$index];
                continue;
            }
            if (!$tokens[$index]->isGivenKind(\T_STRING)) {
                continue;
            }
            $functionName = \strtolower($tokens[$index]->getContent());
            if ('assertinternaltype' !== $functionName && 'assertnotinternaltype' !== $functionName) {
                continue;
            }
            $bracketTokenIndex = $tokens->getNextMeaningfulToken($index);
            if (!$tokens[$bracketTokenIndex]->equals('(')) {
                continue;
            }
            $expectedTypeTokenIndex = $tokens->getNextMeaningfulToken($bracketTokenIndex);
            $expectedTypeToken = $tokens[$expectedTypeTokenIndex];
            if (!$expectedTypeToken->equals([\T_CONSTANT_ENCAPSED_STRING])) {
                continue;
            }
            $expectedType = \trim($expectedTypeToken->getContent(), '\'"');
            if (!isset($this->typeToDedicatedAssertMap[$expectedType])) {
                continue;
            }
            $commaTokenIndex = $tokens->getNextMeaningfulToken($expectedTypeTokenIndex);
            if (!$tokens[$commaTokenIndex]->equals(',')) {
                continue;
            }
            $newAssertion = $this->typeToDedicatedAssertMap[$expectedType];
            if ('assertnotinternaltype' === $functionName) {
                $newAssertion = \str_replace('Is', 'IsNot', $newAssertion);
                $newAssertion = \str_replace('Null', 'NotNull', $newAssertion);
            }
            $nextMeaningfulTokenIndex = $tokens->getNextMeaningfulToken($commaTokenIndex);
            $tokens->overrideRange($index, $nextMeaningfulTokenIndex - 1, [new Token([\T_STRING, $newAssertion]), new Token('(')]);
        }
    }
}
