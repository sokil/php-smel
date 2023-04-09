<?php

declare(strict_types=1);

namespace Sokil\ExpressionLanguage\Expression;

/**
 * Gets value related to node from input data and pass it to internal expression
 */
class NodeExpression extends AbstractExpression
{
    public function __construct(
        private readonly string $nodeName,
        private readonly ComparisonExpression|LogicalExpression $expression
    ) {
    }

    public function evaluate(mixed $data): mixed
    {
        if (!is_array($data)) {
            throw new \LogicException('Node expression must obtain array of node values');
        }

        if (!array_key_exists($this->nodeName, $data)) {
            throw new \InvalidArgumentException('Value not passed for node name');
        }

        return $this->expression->evaluate($data[$this->nodeName]);
    }
}
