<?php

declare(strict_types=1);

namespace Sokil\ExpressionLanguage\Operator;

enum ComparisonOperator: string
{
    case Equals = 'eq';
    case NotEquals = 'neq';
    case LessThan = 'lt';
    case GreaterThan = 'gt';
    case LessThanOrEquals = 'lte';
    case GreaterThanOrEquals = 'gte';
    case In = 'in';
    case NotIn = 'nin';
}
