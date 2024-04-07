<?php

namespace yeh110\annotation\route;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class Pattern
{
    public function __construct(public string $name, public string $value)
    {
    }
}
