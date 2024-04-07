<?php

namespace yeh110\annotation;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Inject
{
    public function __construct(public ?string $abstract = null)
    {
    }
}
