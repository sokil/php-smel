<?php

declare(strict_types=1);

namespace Sokil\ExpressionLanguage;

use Sokil\ExpressionLanguage\Exception\ExpressionInvalidException;
use Sokil\ExpressionLanguage\Parser\ExpressionParser;

class ExpressionEvaluator
{
    private readonly ExpressionParser $parser;

    public function __construct()
    {
        $this->parser = new ExpressionParser();
    }

    /**
     * @param string|array<string, mixed> $expression
     * @param array<string, string|int> $data
     *
     * @throws ExpressionInvalidException
     */
    public function evaluate(
        string|array $expression,
        array $data
    ): mixed {
        $expression = $this->parser->parse($expression);

        return $expression->evaluate($data);
    }
}
