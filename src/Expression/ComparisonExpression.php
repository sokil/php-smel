<?php

declare(strict_types=1);

namespace Sokil\ExpressionLanguage\Expression;

use Sokil\ExpressionLanguage\Operator\ComparisonOperator;

/**
 * Performs comparison operation
 */
class ComparisonExpression extends AbstractExpression
{
    /**
     * @param int|string|array<int|string> $value
     */
    public function __construct(
        private readonly ComparisonOperator $operator,
        private readonly int|string|array $value
    ) {
    }

    public function evaluate(mixed $data): mixed
    {
        return match ($this->operator) {
            ComparisonOperator::GreaterThan => $data > $this->value,
            ComparisonOperator::GreaterThanOrEquals => $data >= $this->value,
            ComparisonOperator::LessThan => $data < $this->value,
            ComparisonOperator::LessThanOrEquals => $data <= $this->value,
            ComparisonOperator::Equals => $data == $this->value,
            ComparisonOperator::In => is_array($this->value) && in_array($data, $this->value),
            ComparisonOperator::NotIn => is_array($this->value) && !in_array($data, $this->value),
            default => throw new \LogicException('Comparison operator not supported'),
        };
    }
}
