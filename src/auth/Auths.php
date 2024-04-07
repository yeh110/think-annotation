<?php

namespace yeh110\annotation\auth;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class Auths
{
    public function __construct(public string $name, public array $options = [])
    {
    }
}
