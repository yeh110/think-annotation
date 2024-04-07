<?php

namespace yeh110\annotation\route;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class Group
{
    public function __construct(public string $name, public array $options = [])
    {
    }
}
