<?php
declare(strict_types=1);

namespace Acme;

function hypotenuse(float $a, float $b): float
{
    return sqrt($a * $a + $b * $b);
}