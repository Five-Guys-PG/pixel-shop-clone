<?php

declare (strict_types=1);
namespace ps_metrics_module_v4_0_6\PhpParser\Node;

use ps_metrics_module_v4_0_6\PhpParser\NodeAbstract;
/**
 * This is a base class for complex types, including nullable types and union types.
 *
 * It does not provide any shared behavior and exists only for type-checking purposes.
 */
abstract class ComplexType extends NodeAbstract
{
}
