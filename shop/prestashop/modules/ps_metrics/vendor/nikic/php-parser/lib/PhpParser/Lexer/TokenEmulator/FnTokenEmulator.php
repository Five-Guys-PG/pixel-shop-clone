<?php

declare (strict_types=1);
namespace ps_metrics_module_v4_0_6\PhpParser\Lexer\TokenEmulator;

use ps_metrics_module_v4_0_6\PhpParser\Lexer\Emulative;
final class FnTokenEmulator extends KeywordEmulator
{
    public function getPhpVersion() : string
    {
        return Emulative::PHP_7_4;
    }
    public function getKeywordString() : string
    {
        return 'fn';
    }
    public function getKeywordToken() : int
    {
        return \T_FN;
    }
}
