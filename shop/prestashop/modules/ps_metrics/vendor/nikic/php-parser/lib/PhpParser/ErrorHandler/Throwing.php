<?php

declare (strict_types=1);
namespace ps_metrics_module_v4_0_6\PhpParser\ErrorHandler;

use ps_metrics_module_v4_0_6\PhpParser\Error;
use ps_metrics_module_v4_0_6\PhpParser\ErrorHandler;
/**
 * Error handler that handles all errors by throwing them.
 *
 * This is the default strategy used by all components.
 */
class Throwing implements ErrorHandler
{
    public function handleError(Error $error)
    {
        throw $error;
    }
}
