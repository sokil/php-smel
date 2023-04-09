<?php

declare(strict_types=1);

namespace Sokil\ExpressionLanguage;

use PHPUnit\Framework\TestCase;

class ExpressionEvaluatorTest extends TestCase
{
    public function equalsPositiveDataProvider()
    {
        return [
            [
                'expression' => [
                    'param1' => 42,
                ],
                'data' => [
                    'param1' => 42,
                ],
            ],
            [
                'expression' => '{"param1": 42}',
                'data' => [
                    'param1' => 42,
                ],
            ],
            [
                'expression' => [
                    'param1' => ['eq' => 42],
                ],
                'data' => [
                    'param1' => 42,
                ],
            ],
            [
                'expression' => '{"param1": {"eq": 42}}',
                'data' => [
                    'param1' => 42,
                ],
            ],
            [
                'expression' => [
                    'param1' => 42,
                    'param2' => 43,
                ],
                'data' => [
                    'param1' => 42,
                    'param2' => 43,
                ],
            ],
            [
                'expression' => [
                    'param1' => ['eq' => 42],
                    'param2' => 43,
                ],
                'data' => [
                    'param1' => 42,
                    'param2' => 43,
                ],
            ],
        ];
    }

    /**
     * @dataProvider equalsPositiveDataProvider
     */
    public function testEqualsPositive(array|string $expression, array $data)
    {
        $evaluator = new ExpressionEvaluator();
        $result = $evaluator->evaluate(
            $expression,
            $data
        );

        $this->assertTrue($result);
    }

    public function equalsNegativeDataProvider()
    {
        return [
            [
                'expression' => [
                    'param1' => 42,
                ],
                'data' => [
                    'param1' => 'some',
                ]
            ],
        ];
    }

    /**
     * @dataProvider equalsNegativeDataProvider
     */

    public function testEqualsNegative(array|string $expression, array $data)
    {
        $evaluator = new ExpressionEvaluator();
        $result = $evaluator->evaluate(
            $expression,
            $data
        );

        $this->assertFalse($result);
    }

    public function invalidExpressionDefinitionDataProvider()
    {
        return [
            [
                'expression' => [
                    'param1' => 42,
                ],
                'data' => []
            ],
            [
                'expression' => [
                    'param1' => 42,
                ],
                'data' => [
                    'other-field' => 42,
                ]
            ],
            [
                'expression' => [
                    'param1' => 42,
                    'param2' => 43,
                ],
                'data' => [
                    'param1' => 42,
                ]
            ],
        ];
    }

    /**
     * @dataProvider invalidExpressionDefinitionDataProvider
     */
    public function testInvalidExpressionDefinition(array|string $expression, array $data)
    {
        $this->expectExceptionMessage('Value not passed for node name');

        $evaluator = new ExpressionEvaluator();

        $evaluator->evaluate(
            $expression,
            $data
        );
    }

    public function testEmptyExpression()
    {
        $this->expectExceptionMessage('Expressions count for logical expression must be greater 0');

        $evaluator = new ExpressionEvaluator();
        $evaluator->evaluate(
            [],
            ['param' => 42]
        );
    }

    public function testStringExpression()
    {
        $evaluator = new ExpressionEvaluator();
        $result = $evaluator->evaluate(
            '{"param1": 42}',
            ['param1' => 42]
        );

        $this->assertTrue($result);
    }

    public function testGreaterPositive()
    {
        $evaluator = new ExpressionEvaluator();
        $result = $evaluator->evaluate(
            [
                'param' => ['gt' => 40]
            ],
            ['param' => 42]
        );

        $this->assertTrue($result);
    }

    public function testGreaterNegative()
    {
        $evaluator = new ExpressionEvaluator();
        $result = $evaluator->evaluate(
            [
                'param' => ['gt' => 50]
            ],
            ['param' => 42]
        );

        $this->assertFalse($result);
    }

    public function testGreaterOrEqualsPositive()
    {
        $evaluator = new ExpressionEvaluator();
        $result = $evaluator->evaluate(
            [
                'param' => ['gte' => 42]
            ],
            ['param' => 42]
        );

        $this->assertTrue($result);
    }

    public function testGreaterOrEqualsNegative()
    {
        $evaluator = new ExpressionEvaluator();
        $result = $evaluator->evaluate(
            [
                'param' => ['gte' => 50]
            ],
            ['param' => 42]
        );

        $this->assertFalse($result);
    }

    public function testLessPositive()
    {
        $evaluator = new ExpressionEvaluator();
        $result = $evaluator->evaluate(
            [
                'param' => ['lt' => 40]
            ],
            ['param' => 32]
        );

        $this->assertTrue($result);
    }

    public function testLessNegative()
    {
        $evaluator = new ExpressionEvaluator();
        $result = $evaluator->evaluate(
            [
                'param' => ['lt' => 40]
            ],
            ['param' => 50]
        );

        $this->assertFalse($result);
    }

    public function testLessOrEqualsPositive()
    {
        $evaluator = new ExpressionEvaluator();
        $result = $evaluator->evaluate(
            [
                'param' => ['gte' => 40]
            ],
            ['param' => 42]
        );

        $this->assertTrue($result);
    }

    public function testLessOrEqualsNegative()
    {
        $evaluator = new ExpressionEvaluator();
        $result = $evaluator->evaluate(
            [
                'param' => ['gte' => 50]
            ],
            ['param' => 42]
        );

        $this->assertFalse($result);
    }

    public function testInPositive()
    {
        $evaluator = new ExpressionEvaluator();
        $result = $evaluator->evaluate(
            [
                'param' => ['in' => ['UKR', 'USA', 'CAN']]
            ],
            ['param' => 'UKR']
        );

        $this->assertTrue($result);
    }

    public function testInNegative()
    {
        $evaluator = new ExpressionEvaluator();
        $result = $evaluator->evaluate(
            [
                'param' => ['in' => ['UKR', 'USA', 'CAN']]
            ],
            ['param' => 'GBR']
        );

        $this->assertFalse($result);
    }

    public function testNotInPositive()
    {
        $evaluator = new ExpressionEvaluator();
        $result = $evaluator->evaluate(
            [
                'param' => ['nin' => ['UKR', 'USA', 'CAN']]
            ],
            ['param' => 'GBR']
        );

        $this->assertTrue($result);
    }

    public function testNotInNegative()
    {
        $evaluator = new ExpressionEvaluator();
        $result = $evaluator->evaluate(
            [
                'param' => ['nin' => ['UKR', 'USA', 'CAN']]
            ],
            ['param' => 'UKR']
        );

        $this->assertFalse($result);
    }
}
