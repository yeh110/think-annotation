<?php

namespace yeh110\annotation\auth;

use Attribute;
use JetBrains\PhpStorm\ExpectedValues;

#[Attribute(Attribute::TARGET_METHOD)]
class Auth
{
    public function __construct(
        public string $name,
        public array  $options = []
    )
    {

    }

}
