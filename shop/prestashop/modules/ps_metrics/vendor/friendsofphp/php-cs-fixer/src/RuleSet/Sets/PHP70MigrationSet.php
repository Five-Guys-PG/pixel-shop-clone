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
namespace ps_metrics_module_v4_0_6\PhpCsFixer\RuleSet\Sets;

use ps_metrics_module_v4_0_6\PhpCsFixer\RuleSet\AbstractMigrationSetDescription;
/**
 * @internal
 */
final class PHP70MigrationSet extends AbstractMigrationSetDescription
{
    public function getRules() : array
    {
        return ['@PHP54Migration' => \true, 'ternary_to_null_coalescing' => \true];
    }
}
