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
namespace ps_metrics_module_v4_0_6\PhpCsFixer\Fixer\Basic;

use ps_metrics_module_v4_0_6\PhpCsFixer\AbstractFixer;
use ps_metrics_module_v4_0_6\PhpCsFixer\Fixer\ConfigurableFixerInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerConfigurationResolver;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FileSpecificCodeSample;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinition;
use ps_metrics_module_v4_0_6\PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use ps_metrics_module_v4_0_6\PhpCsFixer\Preg;
use ps_metrics_module_v4_0_6\PhpCsFixer\StdinFileInfo;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Token;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\Tokens;
use ps_metrics_module_v4_0_6\PhpCsFixer\Tokenizer\TokensAnalyzer;
/**
 * @author Jordi Boggiano <j.boggiano@seld.be>
 * @author Dariusz Rumiński <dariusz.ruminski@gmail.com>
 * @author Bram Gotink <bram@gotink.me>
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 * @author Kuba Werłos <werlos@gmail.com>
 */
final class PsrAutoloadingFixer extends AbstractFixer implements ConfigurableFixerInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDefinition() : FixerDefinitionInterface
    {
        return new FixerDefinition('Classes must be in a path that matches their namespace, be at least one namespace deep and the class name should match the file name.', [new FileSpecificCodeSample('<?php
namespace PhpCsFixer\\FIXER\\Basic;
class InvalidName {}
', new \SplFileInfo(__FILE__)), new FileSpecificCodeSample('<?php
namespace PhpCsFixer\\FIXER\\Basic;
class InvalidName {}
', new \SplFileInfo(__FILE__), ['dir' => './src'])], null, 'This fixer may change your class name, which will break the code that depends on the old name.');
    }
    /**
     * {@inheritdoc}
     */
    public function configure(array $configuration) : void
    {
        parent::configure($configuration);
        if (null !== $this->configuration['dir']) {
            $this->configuration['dir'] = \realpath($this->configuration['dir']);
        }
    }
    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens) : bool
    {
        return $tokens->isAnyTokenKindsFound(Token::getClassyTokenKinds());
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
    public function getPriority() : int
    {
        return -10;
    }
    /**
     * {@inheritdoc}
     */
    public function supports(\SplFileInfo $file) : bool
    {
        if ($file instanceof StdinFileInfo) {
            return \false;
        }
        if ('php' !== $file->getExtension() || 0 === Preg::match('/^[a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff]*$/', $file->getBasename('.php'))) {
            return \false;
        }
        try {
            $tokens = Tokens::fromCode(\sprintf('<?php class %s {}', $file->getBasename('.php')));
            if ($tokens[3]->isKeyword() || $tokens[3]->isMagicConstant()) {
                // name cannot be a class name - detected by PHP 5.x
                return \false;
            }
        } catch (\ParseError $e) {
            // name cannot be a class name - detected by PHP 7.x
            return \false;
        }
        // ignore stubs/fixtures, since they typically contain invalid files for various reasons
        return !Preg::match('{[/\\\\](stub|fixture)s?[/\\\\]}i', $file->getRealPath());
    }
    /**
     * {@inheritdoc}
     */
    protected function createConfigurationDefinition() : FixerConfigurationResolverInterface
    {
        return new FixerConfigurationResolver([(new FixerOptionBuilder('dir', 'If provided, the directory where the project code is placed.'))->setAllowedTypes(['null', 'string'])->setDefault(null)->getOption()]);
    }
    /**
     * {@inheritdoc}
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens) : void
    {
        $tokenAnalyzer = new TokensAnalyzer($tokens);
        if (null !== $this->configuration['dir'] && !\str_starts_with($file->getRealPath(), $this->configuration['dir'])) {
            return;
        }
        $namespace = null;
        $namespaceStartIndex = null;
        $namespaceEndIndex = null;
        $classyName = null;
        $classyIndex = null;
        foreach ($tokens as $index => $token) {
            if ($token->isGivenKind(\T_NAMESPACE)) {
                if (null !== $namespace) {
                    return;
                }
                $namespaceStartIndex = $tokens->getNextMeaningfulToken($index);
                $namespaceEndIndex = $tokens->getNextTokenOfKind($namespaceStartIndex, [';']);
                $namespace = \trim($tokens->generatePartialCode($namespaceStartIndex, $namespaceEndIndex - 1));
            } elseif ($token->isClassy()) {
                if ($tokenAnalyzer->isAnonymousClass($index)) {
                    continue;
                }
                if (null !== $classyName) {
                    return;
                }
                $classyIndex = $tokens->getNextMeaningfulToken($index);
                $classyName = $tokens[$classyIndex]->getContent();
            }
        }
        if (null === $classyName) {
            return;
        }
        $expectedClassyName = $this->calculateClassyName($file, $namespace, $classyName);
        if ($classyName !== $expectedClassyName) {
            $tokens[$classyIndex] = new Token([\T_STRING, $expectedClassyName]);
        }
        if (null === $this->configuration['dir'] || null === $namespace) {
            return;
        }
        if (!\is_dir($this->configuration['dir'])) {
            return;
        }
        $configuredDir = \realpath($this->configuration['dir']);
        $fileDir = \dirname($file->getRealPath());
        if (\strlen($configuredDir) >= \strlen($fileDir)) {
            return;
        }
        $newNamespace = \substr(\str_replace('/', '\\', $fileDir), \strlen($configuredDir) + 1);
        $originalNamespace = \substr($namespace, -\strlen($newNamespace));
        if ($originalNamespace !== $newNamespace && \strtolower($originalNamespace) === \strtolower($newNamespace)) {
            $tokens->clearRange($namespaceStartIndex, $namespaceEndIndex);
            $namespace = \substr($namespace, 0, -\strlen($newNamespace)) . $newNamespace;
            $newNamespace = Tokens::fromCode('<?php namespace ' . $namespace . ';');
            $newNamespace->clearRange(0, 2);
            $newNamespace->clearEmptyTokens();
            $tokens->insertAt($namespaceStartIndex, $newNamespace);
        }
    }
    private function calculateClassyName(\SplFileInfo $file, ?string $namespace, string $currentName) : string
    {
        $name = $file->getBasename('.php');
        $maxNamespace = $this->calculateMaxNamespace($file, $namespace);
        if (null !== $this->configuration['dir']) {
            return ('' !== $maxNamespace ? \str_replace('\\', '_', $maxNamespace) . '_' : '') . $name;
        }
        $namespaceParts = \array_reverse(\explode('\\', $maxNamespace));
        foreach ($namespaceParts as $namespacePart) {
            $nameCandidate = \sprintf('%s_%s', $namespacePart, $name);
            if (\strtolower($nameCandidate) !== \strtolower(\substr($currentName, -\strlen($nameCandidate)))) {
                break;
            }
            $name = $nameCandidate;
        }
        return $name;
    }
    private function calculateMaxNamespace(\SplFileInfo $file, ?string $namespace) : string
    {
        if (null === $this->configuration['dir']) {
            $root = \dirname($file->getRealPath());
            while ($root !== \dirname($root)) {
                $root = \dirname($root);
            }
        } else {
            $root = \realpath($this->configuration['dir']);
        }
        $namespaceAccordingToFileLocation = \trim(\str_replace(\DIRECTORY_SEPARATOR, '\\', \substr(\dirname($file->getRealPath()), \strlen($root))), '\\');
        if (null === $namespace) {
            return $namespaceAccordingToFileLocation;
        }
        $namespaceAccordingToFileLocationPartsReversed = \array_reverse(\explode('\\', $namespaceAccordingToFileLocation));
        $namespacePartsReversed = \array_reverse(\explode('\\', $namespace));
        foreach ($namespacePartsReversed as $key => $namespaceParte) {
            if (!isset($namespaceAccordingToFileLocationPartsReversed[$key])) {
                break;
            }
            if (\strtolower($namespaceParte) !== \strtolower($namespaceAccordingToFileLocationPartsReversed[$key])) {
                break;
            }
            unset($namespaceAccordingToFileLocationPartsReversed[$key]);
        }
        return \implode('\\', \array_reverse($namespaceAccordingToFileLocationPartsReversed));
    }
}
