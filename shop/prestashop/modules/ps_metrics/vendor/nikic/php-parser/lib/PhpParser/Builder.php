<?php

declare (strict_types=1);
namespace ps_metrics_module_v4_0_6\PhpParser;

interface Builder
{
    /**
     * Returns the built node.
     *
     * @return Node The built node
     */
    public function getNode() : Node;
}
