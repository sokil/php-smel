<?php

declare(strict_types=1);

namespace Sokil\ExpressionLanguage\Expression;

use Sokil\ExpressionLanguage\Operator\LogicalOperator;

/**
 * Performs boolean operations between internal expressions
 */
class LogicalExpression extends AbstractExpression
{
    /**
     * @param LogicalOperator $operator
     * @param AbstractExpression[] $expressions
     */
    public function __construct(
        private readonly LogicalOperator $operator,
        private readonly array $expressions,
    ) {
        if (count($this->expressions) === 0) {
            throw new \LogicException('Expressions count for logical expression must be greater 0');
        }

        if ($this->operator === LogicalOperator::Not && count($this->expressions) > 1) {
            throw new \LogicException('Only one expression accepted for "not" operator');
        }
    }

    public function evaluate(mixed $data): mixed
    {
        if ($this->operator == LogicalOperator::Not) {
            return !$this->expressions[0]->evaluate($data);
        } else {
            foreach ($this->expressions as $expression) {
                $evaluationResult = $expression->evaluate($data);
                assert(is_bool($evaluationResult));

                if ($this->operator === LogicalOperator::And && $evaluationResult === false) {
                    return false;
                } elseif ($this->operator === LogicalOperator::Or && $evaluationResult === true) {
                    return true;
                }
            }

            if ($this->operator === LogicalOperator::And) {
                return true;
            } elseif ($this->operator === LogicalOperator::Or) {
                return false;
            } else {
                throw new \LogicException('Unsupported logical operator');
            }
        }
    }
}
