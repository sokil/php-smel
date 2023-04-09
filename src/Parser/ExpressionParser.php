<?php

declare(strict_types=1);

namespace Sokil\ExpressionLanguage\Parser;

use Sokil\ExpressionLanguage\Expression\AbstractExpression;
use Sokil\ExpressionLanguage\Expression\ComparisonExpression;
use Sokil\ExpressionLanguage\Expression\LogicalExpression;
use Sokil\ExpressionLanguage\Expression\NodeExpression;
use Sokil\ExpressionLanguage\Operator\ComparisonOperator;
use Sokil\ExpressionLanguage\Operator\LogicalOperator;
use Sokil\ExpressionLanguage\Exception\ExpressionInvalidException;

class ExpressionParser
{
    /**
     * @param string|array<string, mixed> $expression
     * @param array<string, string>|null $nodeSchema If not null, expression validation by node name and data type
     *
     * @throws ExpressionInvalidException
     */
    public function parse(string|array $expression, array $nodeSchema = null): AbstractExpression
    {
        if (is_string($expression)) {
            /** @var array<string, mixed> $expression */
            $expression = \json_decode($expression, true, flags: JSON_THROW_ON_ERROR);
        }

        // detect initial logical expression
        return $this->parseRecursive($expression, $nodeSchema);
    }

    /**
     * @param array<string, mixed> $expressionScalar
     *
     * @throws ExpressionInvalidException
     */
    private function parseRecursive(array $expressionScalar, array $nodeSchema = null): AbstractExpression
    {
        $nodes = [];
        /** @var mixed $nodeExpressionsScalar */
        foreach ($expressionScalar as $nodeName => $nodeExpressionsScalar) {
            if ($nodeSchema !== null && empty($nodeSchema[$nodeName])) {
                throw new ExpressionInvalidException(sprintf('Node name "%s" unknown', $nodeName));
            }

            if (!is_array($nodeExpressionsScalar)) {
                // shorthand for eq: {"field": 42} instead of {"field": {"eq": 42}}
                $nodeExpressionsScalar = ['eq' => $nodeExpressionsScalar];
            }

            $nodeExpressions = [];
            /** @var int|string $value */
            foreach ($nodeExpressionsScalar as $operator => $value) {
                $nodeExpressions[] = new ComparisonExpression(ComparisonOperator::from($operator), $value);
            }

            $nodes[] = new NodeExpression(
                $nodeName,
                new LogicalExpression(LogicalOperator::And, $nodeExpressions)
            );
        }

        return new LogicalExpression(LogicalOperator::And, $nodes);
    }
}
