<?php

/**
 * Test fixture.
 *
 * @see \PHP_CodeSniffer\Tests\Core\Ruleset\SniffDeprecationTest
 */
namespace ps_metrics_module_v4_0_6\Fixtures\Sniffs\DeprecatedInvalid;

use ps_metrics_module_v4_0_6\PHP_CodeSniffer\Files\File;
use ps_metrics_module_v4_0_6\PHP_CodeSniffer\Sniffs\DeprecatedSniff;
use ps_metrics_module_v4_0_6\PHP_CodeSniffer\Sniffs\Sniff;
use stdClass;
class InvalidDeprecationMessageSniff implements Sniff, DeprecatedSniff
{
    public function getDeprecationVersion()
    {
        return 'dummy';
    }
    public function getRemovalVersion()
    {
        return 'dummy';
    }
    public function getDeprecationMessage()
    {
        return new stdClass();
    }
    public function register()
    {
        return [\T_WHITESPACE];
    }
    public function process(File $phpcsFile, $stackPtr)
    {
        // Do something.
    }
}
