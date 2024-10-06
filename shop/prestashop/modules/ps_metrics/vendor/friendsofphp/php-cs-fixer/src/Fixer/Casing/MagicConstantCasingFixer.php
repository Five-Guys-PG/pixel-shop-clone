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
namespace ps_metrics_module_v4_0_6\PhpCsFixer\Fixer\Casing;

use ps_metrics_module_v4_0_6\PhpCsFixer\AbstractFixer;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\CodeSample;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinition;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\CT;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Token;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Tokens;
/**
 * @author ntzm
 */
final class MagicConstantCasingFixer extends AbstractFixer
{
    /**
     * {@inheritdoc}
     */
    public function getDefinition() : FixerDefinitionInterface
    {
        return new FixerDefinition('Magic constants should be referred to using the correct casing.', [new CodeSample("<?php\necho __dir__;\n")]);
    }
    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens) : bool
    {
        return $tokens->isAnyTokenKindsFound($this->getMagicConstantTokens());
    }
    /**
     * {@inheritdoc}
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens) : void
    {
        $magicConstants = $this->getMagicConstants();
        $magicConstantTokens = $this->getMagicConstantTokens();
        foreach ($tokens as $index => $token) {
            if ($token->isGivenKind($magicConstantTokens)) {
                $tokens[$index] = new Token([$token->getId(), $magicConstants[$token->getId()]]);
            }
        }
    }
    /**
     * @return array<int, string>
     */
    private function getMagicConstants() : array
    {
        static $magicConstants = null;
        if (null === $magicConstants) {
            $magicConstants = [\T_LINE => '__LINE__', \T_FILE => '__FILE__', \T_DIR => '__DIR__', \T_FUNC_C => '__FUNCTION__', \T_CLASS_C => '__CLASS__', \T_METHOD_C => '__METHOD__', \T_NS_C => '__NAMESPACE__', CT::T_CLASS_CONSTANT => 'class', \T_TRAIT_C => '__TRAIT__'];
        }
        return $magicConstants;
    }
    /**
     * @return array<int>
     */
    private function getMagicConstantTokens() : array
    {
        static $magicConstantTokens = null;
        if (null === $magicConstantTokens) {
            $magicConstantTokens = \array_keys($this->getMagicConstants());
        }
        return $magicConstantTokens;
    }
}
