<?php

declare (strict_types=1);
namespace ps_metrics_module_v4_0_6\PhpParser\Node\Scalar\MagicConst;

use ps_metrics_module_v4_0_6\PhpParser\Node\Scalar\MagicConst;
class Method extends MagicConst
{
    public function getName() : string
    {
        return '__METHOD__';
    }
    public function getType() : string
    {
        return 'Scalar_MagicConst_Method';
    }
}
