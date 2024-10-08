<?php

namespace ps_metrics_module_v4_0_6\PrestaShop\PsAccountsInstaller\Tests;

use ps_metrics_module_v4_0_6\Faker\Generator;
class TestCase extends \ps_metrics_module_v4_0_6\PHPUnit\Framework\TestCase
{
    /**
     * @var Generator
     */
    public $faker;
    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->faker = \ps_metrics_module_v4_0_6\Faker\Factory::create();
    }
}
