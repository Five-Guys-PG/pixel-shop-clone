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

use ps_metrics_module_v4_0_6\PhpCsFixer\AbstractFixer;
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
/**
 * @author Dariusz Rumiński <dariusz.ruminski@gmail.com>
 */
final class PhpUnitNamespacedFixer extends AbstractFixer implements ConfigurableFixerInterface
{
    /**
     * @var string
     */
    private $originalClassRegEx;
    /**
     * Class Mappings.
     *
     *  * [original classname => new classname] Some classes which match the
     *    original class regular expression do not have a same-compound name-
     *    space class and need a dedicated translation table. This trans-
     *    lation table is defined in @see configure.
     *
     * @var array|string[] Class Mappings
     */
    private $classMap;
    /**
     * {@inheritdoc}
     */
    public function getDefinition() : FixerDefinitionInterface
    {
        $codeSample = '<?php
final class MyTest extends \\PHPUnit_Framework_TestCase
{
    public function testSomething()
    {
        PHPUnit_Framework_Assert::assertTrue(true);
    }
}
';
        return new FixerDefinition('PHPUnit classes MUST be used in namespaced version, e.g. `\\PHPUnit\\Framework\\TestCase` instead of `\\PHPUnit_Framework_TestCase`.', [new CodeSample($codeSample), new CodeSample($codeSample, ['target' => PhpUnitTargetVersion::VERSION_4_8])], "PHPUnit v6 has finally fully switched to namespaces.\n" . "You could start preparing the upgrade by switching from non-namespaced TestCase to namespaced one.\n" . 'Forward compatibility layer (`\\PHPUnit\\Framework\\TestCase` class) was backported to PHPUnit v4.8.35 and PHPUnit v5.4.0.' . "\n" . 'Extended forward compatibility layer (`PHPUnit\\Framework\\Assert`, `PHPUnit\\Framework\\BaseTestListener`, `PHPUnit\\Framework\\TestListener` classes) was introduced in v5.7.0.' . "\n", 'Risky when PHPUnit classes are overridden or not accessible, or when project has PHPUnit incompatibilities.');
    }
    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens) : bool
    {
        return $tokens->isTokenKindFound(\T_STRING);
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
     */
    public function configure(array $configuration) : void
    {
        parent::configure($configuration);
        if (PhpUnitTargetVersion::fulfills($this->configuration['target'], PhpUnitTargetVersion::VERSION_6_0)) {
            $this->originalClassRegEx = '/^PHPUnit_\\w+$/i';
            // @noinspection ClassConstantCanBeUsedInspection
            $this->classMap = ['PHPUnit_Extensions_PhptTestCase' => 'ps_metrics_module_v4_0_6\\PHPUnit\\Runner\\PhptTestCase', 'PHPUnit_Framework_Constraint' => 'ps_metrics_module_v4_0_6\\PHPUnit\\Framework\\Constraint\\Constraint', 'PHPUnit_Framework_Constraint_StringMatches' => 'ps_metrics_module_v4_0_6\\PHPUnit\\Framework\\Constraint\\StringMatchesFormatDescription', 'PHPUnit_Framework_Constraint_JsonMatches_ErrorMessageProvider' => 'ps_metrics_module_v4_0_6\\PHPUnit\\Framework\\Constraint\\JsonMatchesErrorMessageProvider', 'PHPUnit_Framework_Constraint_PCREMatch' => 'ps_metrics_module_v4_0_6\\PHPUnit\\Framework\\Constraint\\RegularExpression', 'PHPUnit_Framework_Constraint_ExceptionMessageRegExp' => 'ps_metrics_module_v4_0_6\\PHPUnit\\Framework\\Constraint\\ExceptionMessageRegularExpression', 'PHPUnit_Framework_Constraint_And' => 'ps_metrics_module_v4_0_6\\PHPUnit\\Framework\\Constraint\\LogicalAnd', 'PHPUnit_Framework_Constraint_Or' => 'ps_metrics_module_v4_0_6\\PHPUnit\\Framework\\Constraint\\LogicalOr', 'PHPUnit_Framework_Constraint_Not' => 'ps_metrics_module_v4_0_6\\PHPUnit\\Framework\\Constraint\\LogicalNot', 'PHPUnit_Framework_Constraint_Xor' => 'ps_metrics_module_v4_0_6\\PHPUnit\\Framework\\Constraint\\LogicalXor', 'PHPUnit_Framework_Error' => 'ps_metrics_module_v4_0_6\\PHPUnit\\Framework\\Error\\Error', 'PHPUnit_Framework_TestSuite_DataProvider' => 'ps_metrics_module_v4_0_6\\PHPUnit\\Framework\\DataProviderTestSuite', 'PHPUnit_Framework_MockObject_Invocation_Static' => 'ps_metrics_module_v4_0_6\\PHPUnit\\Framework\\MockObject\\Invocation\\StaticInvocation', 'PHPUnit_Framework_MockObject_Invocation_Object' => 'ps_metrics_module_v4_0_6\\PHPUnit\\Framework\\MockObject\\Invocation\\ObjectInvocation', 'PHPUnit_Framework_MockObject_Stub_Return' => 'ps_metrics_module_v4_0_6\\PHPUnit\\Framework\\MockObject\\Stub\\ReturnStub', 'PHPUnit_Runner_Filter_Group_Exclude' => 'ps_metrics_module_v4_0_6\\PHPUnit\\Runner\\Filter\\ExcludeGroupFilterIterator', 'PHPUnit_Runner_Filter_Group_Include' => 'ps_metrics_module_v4_0_6\\PHPUnit\\Runner\\Filter\\IncludeGroupFilterIterator', 'PHPUnit_Runner_Filter_Test' => 'ps_metrics_module_v4_0_6\\PHPUnit\\Runner\\Filter\\NameFilterIterator', 'PHPUnit_Util_PHP' => 'ps_metrics_module_v4_0_6\\PHPUnit\\Util\\PHP\\AbstractPhpProcess', 'PHPUnit_Util_PHP_Default' => 'ps_metrics_module_v4_0_6\\PHPUnit\\Util\\PHP\\DefaultPhpProcess', 'PHPUnit_Util_PHP_Windows' => 'ps_metrics_module_v4_0_6\\PHPUnit\\Util\\PHP\\WindowsPhpProcess', 'PHPUnit_Util_Regex' => 'ps_metrics_module_v4_0_6\\PHPUnit\\Util\\RegularExpression', 'PHPUnit_Util_TestDox_ResultPrinter_XML' => 'ps_metrics_module_v4_0_6\\PHPUnit\\Util\\TestDox\\XmlResultPrinter', 'PHPUnit_Util_TestDox_ResultPrinter_HTML' => 'ps_metrics_module_v4_0_6\\PHPUnit\\Util\\TestDox\\HtmlResultPrinter', 'PHPUnit_Util_TestDox_ResultPrinter_Text' => 'ps_metrics_module_v4_0_6\\PHPUnit\\Util\\TestDox\\TextResultPrinter', 'PHPUnit_Util_TestSuiteIterator' => 'ps_metrics_module_v4_0_6\\PHPUnit\\Framework\\TestSuiteIterator', 'PHPUnit_Util_XML' => 'ps_metrics_module_v4_0_6\\PHPUnit\\Util\\Xml'];
        } elseif (PhpUnitTargetVersion::fulfills($this->configuration['target'], PhpUnitTargetVersion::VERSION_5_7)) {
            $this->originalClassRegEx = '/^PHPUnit_Framework_TestCase|PHPUnit_Framework_Assert|PHPUnit_Framework_BaseTestListener|PHPUnit_Framework_TestListener$/i';
            $this->classMap = [];
        } else {
            $this->originalClassRegEx = '/^PHPUnit_Framework_TestCase$/i';
            $this->classMap = [];
        }
    }
    /**
     * {@inheritdoc}
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens) : void
    {
        $importedOriginalClassesMap = [];
        $currIndex = 0;
        while (\true) {
            $currIndex = $tokens->getNextTokenOfKind($currIndex, [[\T_STRING]]);
            if (null === $currIndex) {
                break;
            }
            $prevIndex = $tokens->getPrevMeaningfulToken($currIndex);
            if ($tokens[$prevIndex]->isGivenKind([\T_CONST, \T_DOUBLE_COLON])) {
                continue;
            }
            $originalClass = $tokens[$currIndex]->getContent();
            if (1 !== Preg::match($this->originalClassRegEx, $originalClass)) {
                ++$currIndex;
                continue;
            }
            $substituteTokens = $this->generateReplacement($originalClass);
            $tokens->clearAt($currIndex);
            $tokens->insertAt($currIndex, isset($importedOriginalClassesMap[$originalClass]) ? $substituteTokens[$substituteTokens->getSize() - 1] : $substituteTokens);
            $prevIndex = $tokens->getPrevMeaningfulToken($currIndex);
            if ($tokens[$prevIndex]->isGivenKind(\T_USE)) {
                $importedOriginalClassesMap[$originalClass] = \true;
            } elseif ($tokens[$prevIndex]->isGivenKind(\T_NS_SEPARATOR)) {
                $prevIndex = $tokens->getPrevMeaningfulToken($prevIndex);
                if ($tokens[$prevIndex]->isGivenKind(\T_USE)) {
                    $importedOriginalClassesMap[$originalClass] = \true;
                }
            }
        }
    }
    /**
     * {@inheritdoc}
     */
    protected function createConfigurationDefinition() : FixerConfigurationResolverInterface
    {
        return new FixerConfigurationResolver([(new FixerOptionBuilder('target', 'Target version of PHPUnit.'))->setAllowedTypes(['string'])->setAllowedValues([PhpUnitTargetVersion::VERSION_4_8, PhpUnitTargetVersion::VERSION_5_7, PhpUnitTargetVersion::VERSION_6_0, PhpUnitTargetVersion::VERSION_NEWEST])->setDefault(PhpUnitTargetVersion::VERSION_NEWEST)->getOption()]);
    }
    private function generateReplacement(string $originalClassName) : Tokens
    {
        $delimiter = '_';
        $string = $originalClassName;
        if (isset($this->classMap[$originalClassName])) {
            $delimiter = '\\';
            $string = $this->classMap[$originalClassName];
        }
        $parts = \explode($delimiter, $string);
        $tokensArray = [];
        while (!empty($parts)) {
            $tokensArray[] = new Token([\T_STRING, \array_shift($parts)]);
            if (!empty($parts)) {
                $tokensArray[] = new Token([\T_NS_SEPARATOR, '\\']);
            }
        }
        return Tokens::fromArray($tokensArray);
    }
}
