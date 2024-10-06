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
namespace ps_metrics_module_v4_0_6\PhpCsFixer\RuleSet;

/**
 * @internal
 */
interface RuleSetDescriptionInterface
{
    public function getDescription() : string;
    public function getName() : string;
    /**
     * Get all rules from rules set.
     */
    public function getRules() : array;
    public function isRisky() : bool;
}
