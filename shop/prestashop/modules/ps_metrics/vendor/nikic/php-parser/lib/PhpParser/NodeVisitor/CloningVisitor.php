<?php

declare (strict_types=1);
namespace ps_metrics_module_v4_0_6\PhpParser\NodeVisitor;

use ps_metrics_module_v4_0_6\PhpParser\Node;
use ps_metrics_module_v4_0_6\PhpParser\NodeVisitorAbstract;
/**
 * Visitor cloning all nodes and linking to the original nodes using an attribute.
 *
 * This visitor is required to perform format-preserving pretty prints.
 */
class CloningVisitor extends NodeVisitorAbstract
{
    public function enterNode(Node $origNode)
    {
        $node = clone $origNode;
        $node->setAttribute('origNode', $origNode);
        return $node;
    }
}
