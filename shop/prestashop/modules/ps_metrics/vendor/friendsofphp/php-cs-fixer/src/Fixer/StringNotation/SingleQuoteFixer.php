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
namespace ps_metrics_module_v4_0_6\PhpCsFixer\Fixer\StringNotation;

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
 * @author Gregor Harlan <gharlan@web.de>
 */
final class SingleQuoteFixer extends AbstractFixer implements ConfigurableFixerInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDefinition() : FixerDefinitionInterface
    {
        $codeSample = <<<'EOF'
<?php

namespace ps_metrics_module_v4_0_6;

$a = "sample";
$b = "sample with 'single-quotes'";

EOF;
        return new FixerDefinition('Convert double quotes to single quotes for simple strings.', [new CodeSample($codeSample), new CodeSample($codeSample, ['strings_containing_single_quote_chars' => \true])]);
    }
    /**
     * {@inheritdoc}
     *
     * Must run after BacktickToShellExecFixer, EscapeImplicitBackslashesFixer.
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
        return $tokens->isTokenKindFound(\T_CONSTANT_ENCAPSED_STRING);
    }
    /**
     * {@inheritdoc}
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens) : void
    {
        foreach ($tokens as $index => $token) {
            if (!$token->isGivenKind(\T_CONSTANT_ENCAPSED_STRING)) {
                continue;
            }
            $content = $token->getContent();
            $prefix = '';
            if ('b' === \strtolower($content[0])) {
                $prefix = $content[0];
                $content = \substr($content, 1);
            }
            if ('"' === $content[0] && (\true === $this->configuration['strings_containing_single_quote_chars'] || !\str_contains($content, "'")) && !Preg::match('/(?<!\\\\)(?:\\\\{2})*\\\\(?!["$\\\\])/', $content)) {
                $content = \substr($content, 1, -1);
                $content = \str_replace(['\\"', '\\$', '\''], ['"', '$', '\\\''], $content);
                $tokens[$index] = new Token([\T_CONSTANT_ENCAPSED_STRING, $prefix . '\'' . $content . '\'']);
            }
        }
    }
    /**
     * {@inheritdoc}
     */
    protected function createConfigurationDefinition() : FixerConfigurationResolverInterface
    {
        return new FixerConfigurationResolver([(new FixerOptionBuilder('strings_containing_single_quote_chars', 'Whether to fix double-quoted strings that contains single-quotes.'))->setAllowedTypes(['bool'])->setDefault(\false)->getOption()]);
    }
}
