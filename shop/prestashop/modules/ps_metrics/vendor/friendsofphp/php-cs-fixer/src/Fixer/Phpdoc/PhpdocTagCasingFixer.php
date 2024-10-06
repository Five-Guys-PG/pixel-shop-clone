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

use ps_metrics_module_v4_0_6\PhpCsFixer\AbstractProxyFixer;
use ps_metrics_module_v4_0_6\PhpCsFixer\ConfigurationException\InvalidConfigurationException;
use ps_metrics_module_v4_0_6\PhpCsFixer\ConfigurationException\InvalidFixerConfigurationException;
use ps_metrics_module_v4_0_6\PhpCsFixer\Fixer\ConfigurableFixerInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerConfigurationResolver;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\CodeSample;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinition;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\Preg;
final class PhpdocTagCasingFixer extends AbstractProxyFixer implements ConfigurableFixerInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDefinition() : FixerDefinitionInterface
    {
        return new FixerDefinition('Fixes casing of PHPDoc tags.', [new CodeSample("<?php\n/**\n * @inheritdoc\n */\n"), new CodeSample("<?php\n/**\n * @inheritdoc\n * @Foo\n */\n", ['tags' => ['foo']])]);
    }
    /**
     * {@inheritdoc}
     *
     * Must run before PhpdocAlignFixer.
     * Must run after AlignMultilineCommentFixer, CommentToPhpdocFixer, PhpdocIndentFixer, PhpdocScalarFixer, PhpdocToCommentFixer, PhpdocTypesFixer.
     */
    public function getPriority() : int
    {
        return parent::getPriority();
    }
    public function configure(array $configuration) : void
    {
        parent::configure($configuration);
        $replacements = [];
        foreach ($this->configuration['tags'] as $tag) {
            $replacements[$tag] = $tag;
        }
        /** @var GeneralPhpdocTagRenameFixer $generalPhpdocTagRenameFixer */
        $generalPhpdocTagRenameFixer = $this->proxyFixers['general_phpdoc_tag_rename'];
        try {
            $generalPhpdocTagRenameFixer->configure(['fix_annotation' => \true, 'fix_inline' => \true, 'replacements' => $replacements, 'case_sensitive' => \false]);
        } catch (InvalidConfigurationException $exception) {
            throw new InvalidFixerConfigurationException($this->getName(), Preg::replace('/^\\[.+?\\] /', '', $exception->getMessage()), $exception);
        }
    }
    /**
     * {@inheritdoc}
     */
    protected function createConfigurationDefinition() : FixerConfigurationResolverInterface
    {
        return new FixerConfigurationResolver([(new FixerOptionBuilder('tags', 'List of tags to fix with their expected casing.'))->setAllowedTypes(['array'])->setDefault(['inheritDoc'])->getOption()]);
    }
    /**
     * {@inheritdoc}
     */
    protected function createProxyFixers() : array
    {
        return [new GeneralPhpdocTagRenameFixer()];
    }
}
