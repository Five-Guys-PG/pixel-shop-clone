<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Symfony\Component\String;

if (!\function_exists(\Symfony\Component\String\u::class)) {
    function u(?string $string = '') : \Symfony\Component\String\UnicodeString
    {
        return new \Symfony\Component\String\UnicodeString($string ?? '');
    }
}
if (!\function_exists(\Symfony\Component\String\b::class)) {
    function b(?string $string = '') : \Symfony\Component\String\ByteString
    {
        return new \Symfony\Component\String\ByteString($string ?? '');
    }
}
if (!\function_exists(\Symfony\Component\String\s::class)) {
    /**
     * @return UnicodeString|ByteString
     */
    function s(?string $string = '') : \Symfony\Component\String\AbstractString
    {
        $string = $string ?? '';
        return \preg_match('//u', $string) ? new \Symfony\Component\String\UnicodeString($string) : new \Symfony\Component\String\ByteString($string);
    }
}
