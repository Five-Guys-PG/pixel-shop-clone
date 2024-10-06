<?php

/**
 * Tests for the \PHP_CodeSniffer\Autoload::determineLoadedClass method.
 *
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006-2015 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 */
namespace ps_metrics_module_v4_0_6\PHP_CodeSniffer\Tests\Core\Autoloader;

use ps_metrics_module_v4_0_6\PHP_CodeSniffer\Autoload;
use ps_metrics_module_v4_0_6\PHPUnit\Framework\TestCase;
/**
 * Tests for the \PHP_CodeSniffer\Autoload::determineLoadedClass method.
 *
 * @covers \PHP_CodeSniffer\Autoload::determineLoadedClass
 */
final class DetermineLoadedClassTest extends TestCase
{
    /**
     * Load the test files.
     *
     * @beforeClass
     *
     * @return void
     */
    public static function includeFixture()
    {
        include __DIR__ . '/TestFiles/Sub/C.inc';
    }
    //end includeFixture()
    /**
     * Test for when class list is ordered.
     *
     * @return void
     */
    public function testOrdered()
    {
        $classesBeforeLoad = ['classes' => [], 'interfaces' => [], 'traits' => []];
        $classesAfterLoad = ['classes' => ['ps_metrics_module_v4_0_6\\PHP_CodeSniffer\\Tests\\Core\\Autoloader\\A', 'ps_metrics_module_v4_0_6\\PHP_CodeSniffer\\Tests\\Core\\Autoloader\\B', 'ps_metrics_module_v4_0_6\\PHP_CodeSniffer\\Tests\\Core\\Autoloader\\C', 'ps_metrics_module_v4_0_6\\PHP_CodeSniffer\\Tests\\Core\\Autoloader\\Sub\\C'], 'interfaces' => [], 'traits' => []];
        $className = Autoload::determineLoadedClass($classesBeforeLoad, $classesAfterLoad);
        $this->assertEquals('ps_metrics_module_v4_0_6\\PHP_CodeSniffer\\Tests\\Core\\Autoloader\\Sub\\C', $className);
    }
    //end testOrdered()
    /**
     * Test for when class list is out of order.
     *
     * @return void
     */
    public function testUnordered()
    {
        $classesBeforeLoad = ['classes' => [], 'interfaces' => [], 'traits' => []];
        $classesAfterLoad = ['classes' => ['ps_metrics_module_v4_0_6\\PHP_CodeSniffer\\Tests\\Core\\Autoloader\\A', 'ps_metrics_module_v4_0_6\\PHP_CodeSniffer\\Tests\\Core\\Autoloader\\Sub\\C', 'ps_metrics_module_v4_0_6\\PHP_CodeSniffer\\Tests\\Core\\Autoloader\\C', 'ps_metrics_module_v4_0_6\\PHP_CodeSniffer\\Tests\\Core\\Autoloader\\B'], 'interfaces' => [], 'traits' => []];
        $className = Autoload::determineLoadedClass($classesBeforeLoad, $classesAfterLoad);
        $this->assertEquals('ps_metrics_module_v4_0_6\\PHP_CodeSniffer\\Tests\\Core\\Autoloader\\Sub\\C', $className);
        $classesAfterLoad = ['classes' => ['ps_metrics_module_v4_0_6\\PHP_CodeSniffer\\Tests\\Core\\Autoloader\\A', 'ps_metrics_module_v4_0_6\\PHP_CodeSniffer\\Tests\\Core\\Autoloader\\C', 'ps_metrics_module_v4_0_6\\PHP_CodeSniffer\\Tests\\Core\\Autoloader\\Sub\\C', 'ps_metrics_module_v4_0_6\\PHP_CodeSniffer\\Tests\\Core\\Autoloader\\B'], 'interfaces' => [], 'traits' => []];
        $className = Autoload::determineLoadedClass($classesBeforeLoad, $classesAfterLoad);
        $this->assertEquals('ps_metrics_module_v4_0_6\\PHP_CodeSniffer\\Tests\\Core\\Autoloader\\Sub\\C', $className);
        $classesAfterLoad = ['classes' => ['ps_metrics_module_v4_0_6\\PHP_CodeSniffer\\Tests\\Core\\Autoloader\\Sub\\C', 'ps_metrics_module_v4_0_6\\PHP_CodeSniffer\\Tests\\Core\\Autoloader\\A', 'ps_metrics_module_v4_0_6\\PHP_CodeSniffer\\Tests\\Core\\Autoloader\\C', 'ps_metrics_module_v4_0_6\\PHP_CodeSniffer\\Tests\\Core\\Autoloader\\B'], 'interfaces' => [], 'traits' => []];
        $className = Autoload::determineLoadedClass($classesBeforeLoad, $classesAfterLoad);
        $this->assertEquals('ps_metrics_module_v4_0_6\\PHP_CodeSniffer\\Tests\\Core\\Autoloader\\Sub\\C', $className);
    }
    //end testUnordered()
}
//end class
