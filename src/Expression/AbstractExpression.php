<?php

declare(strict_types=1);

namespace Sokil\ExpressionLanguage\Expression;

abstract class AbstractExpression
{
    abstract public function evaluate(mixed $data): mixed;
}
