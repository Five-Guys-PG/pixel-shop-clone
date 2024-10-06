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
namespace ps_metrics_module_v4_0_6\PhpCsFixer\Fixer\ArrayNotation;

use ps_metrics_module_v4_0_6\PhpCsFixer\AbstractFixer;
use ps_metrics_module_v4_0_6\PhpCsFixer\Fixer\ConfigurableFixerInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerConfigurationResolver;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\InvalidOptionsForEnvException;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\CodeSample;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinition;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\VersionSpecification;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\VersionSpecificCodeSample;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\CT;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Tokens;
use Symfony\Component\OptionsResolver\Options;
/**
 * @author Adam Marczuk <adam@marczuk.info>
 */
final class NoWhitespaceBeforeCommaInArrayFixer extends AbstractFixer implements ConfigurableFixerInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDefinition() : FixerDefinitionInterface
    {
        return new FixerDefinition('In array declaration, there MUST NOT be a whitespace before each comma.', [new CodeSample("<?php \$x = array(1 , \"2\");\n"), new VersionSpecificCodeSample(<<<'SAMPLE'
<?php

namespace ps_metrics_module_v4_0_6;

$x = [<<<EOD
foo
EOD
, 'bar'];

SAMPLE
, new VersionSpecification(70300), ['after_heredoc' => \true])]);
    }
    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens) : bool
    {
        return $tokens->isAnyTokenKindsFound([\T_ARRAY, CT::T_ARRAY_SQUARE_BRACE_OPEN]);
    }
    /**
     * {@inheritdoc}
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens) : void
    {
        for ($index = $tokens->count() - 1; $index >= 0; --$index) {
            if ($tokens[$index]->isGivenKind([\T_ARRAY, CT::T_ARRAY_SQUARE_BRACE_OPEN])) {
                $this->fixSpacing($index, $tokens);
            }
        }
    }
    /**
     * {@inheritdoc}
     */
    protected function createConfigurationDefinition() : FixerConfigurationResolverInterface
    {
        return new FixerConfigurationResolver([(new FixerOptionBuilder('after_heredoc', 'Whether the whitespace between heredoc end and comma should be removed.'))->setAllowedTypes(['bool'])->setDefault(\false)->setNormalizer(static function (Options $options, $value) {
            if (\PHP_VERSION_ID < 70300 && $value) {
                throw new InvalidOptionsForEnvException('"after_heredoc" option can only be enabled with PHP 7.3+.');
            }
            return $value;
        })->getOption()]);
    }
    /**
     * Method to fix spacing in array declaration.
     */
    private function fixSpacing(int $index, Tokens $tokens) : void
    {
        if ($tokens[$index]->isGivenKind(CT::T_ARRAY_SQUARE_BRACE_OPEN)) {
            $startIndex = $index;
            $endIndex = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_ARRAY_SQUARE_BRACE, $startIndex);
        } else {
            $startIndex = $tokens->getNextTokenOfKind($index, ['(']);
            $endIndex = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_PARENTHESIS_BRACE, $startIndex);
        }
        for ($i = $endIndex - 1; $i > $startIndex; --$i) {
            $i = $this->skipNonArrayElements($i, $tokens);
            $currentToken = $tokens[$i];
            $prevIndex = $tokens->getPrevNonWhitespace($i - 1);
            if ($currentToken->equals(',') && !$tokens[$prevIndex]->isComment() && (\true === $this->configuration['after_heredoc'] || !$tokens[$prevIndex]->equals([\T_END_HEREDOC]))) {
                $tokens->removeLeadingWhitespace($i);
            }
        }
    }
    /**
     * Method to move index over the non-array elements like function calls or function declarations.
     *
     * @return int New index
     */
    private function skipNonArrayElements(int $index, Tokens $tokens) : int
    {
        if ($tokens[$index]->equals('}')) {
            return $tokens->findBlockStart(Tokens::BLOCK_TYPE_CURLY_BRACE, $index);
        }
        if ($tokens[$index]->equals(')')) {
            $startIndex = $tokens->findBlockStart(Tokens::BLOCK_TYPE_PARENTHESIS_BRACE, $index);
            $startIndex = $tokens->getPrevMeaningfulToken($startIndex);
            if (!$tokens[$startIndex]->isGivenKind([\T_ARRAY, CT::T_ARRAY_SQUARE_BRACE_OPEN])) {
                return $startIndex;
            }
        }
        return $index;
    }
}
