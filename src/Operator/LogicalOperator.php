<?php

declare(strict_types=1);

namespace Sokil\ExpressionLanguage\Operator;

enum LogicalOperator: string
{
    case And = 'and';
    case Not = 'not';
    case Or = 'or';
}
