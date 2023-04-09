<?php

declare(strict_types=1);

namespace Sokil\ExpressionLanguage;

use Sokil\ExpressionLanguage\Parser\ExpressionParser;
use Sokil\ExpressionLanguage\Exception\ExpressionInvalidException;

class ExpressionValidator
{
    private readonly ExpressionParser $parser;

    public function __construct()
    {
        $this->parser = new ExpressionParser();
    }

    /**
     * @param string|array<string, mixed> $expression
     * @param array<string, string> $nodeSchema Expression validation by node name and data type
     *
     * @throws ExpressionInvalidException
     */
    public function validate(string|array $expression, array $nodeSchema): void
    {
        try {
            $this->parser->parse($expression, $nodeSchema);
        } catch (ExpressionInvalidException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new ExpressionInvalidException('Expression is invalid', previous: $e);
        }
    }
}
