<?php

declare(strict_types=1);

namespace Sokil\ExpressionLanguage;

use Sokil\ExpressionLanguage\Exception\ExpressionInvalidException;
use PHPUnit\Framework\TestCase;

class ExpressionValidatorTest extends TestCase
{
    public function validDataProvider()
    {
        return [
            [
                'expression' => [
                    'field1' => ['gt' => 42]
                ],
                'validFields' => [
                    'field1' => 'integer',
                ],
            ],
            [
                'expression' => [
                    'field1' => ['gt' => 42, 'lt' => 47]
                ],
                'validFields' => [
                    'field1' => 'integer',
                ],
            ],
            [
                'expression' => [
                    'field1' => ['gt' => 42, 'lt' => 47],
                    'field2' => ['gt' => 42, 'lt' => 47],
                ],
                'validFields' => [
                    'field1' => 'integer',
                    'field2' => 'integer',
                ],
            ],
        ];
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testValid(array $expression, array $allowedNodeNames)
    {
        $this->expectNotToPerformAssertions();

        $validator = new ExpressionValidator();

        $validator->validate($expression, $allowedNodeNames);
    }

    public function invalidDataProvider()
    {
        return [
            [
                'expression' => [
                    'field1' => ['unknown-operator' => 44],
                    'field2' => ['gt' => 42, 'lt' => 47],
                ],
                'validFields' => [
                    'field1' => 'integer',
                    'field2' => 'integer',
                ],
            ],
            [
                'expression' => [
                    'field1' => ['unknown-operator' => ['invalid-value' => 42]],
                    'field2' => ['gt' => 42, 'lt' => 47],
                ],
                'validFields' => [
                    'field1' => 'integer',
                    'field2' => 'integer',
                ],
            ],
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testInvalid(array $expression, array $allowedNodeNames)
    {
        $this->expectException(ExpressionInvalidException::class);

        $validator = new ExpressionValidator();

        $validator->validate($expression, $allowedNodeNames);
    }
}
