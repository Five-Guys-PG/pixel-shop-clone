<?php

declare (strict_types=1);
namespace ps_metrics_module_v4_0_6\PhpParser\Node\Expr\BinaryOp;

use ps_metrics_module_v4_0_6\PhpParser\Node\Expr\BinaryOp;
class LogicalOr extends BinaryOp
{
    public function getOperatorSigil() : string
    {
        return 'or';
    }
    public function getType() : string
    {
        return 'Expr_BinaryOp_LogicalOr';
    }
}
