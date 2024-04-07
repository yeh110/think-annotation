<?php

namespace yeh110\annotation\route;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class Validate
{
    public function __construct(
        public mixed   $value,
        public array   $message = [],
        public bool    $batch = true,
        public ?string $scene = null
    )
    {
    }
}
