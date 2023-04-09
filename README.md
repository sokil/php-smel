# Structural Matching Expression Language

## Installation

### Composer

```
composer req sokil/php-smel
```

## Usage

```php
<?php

$evaluator = new ExpressionEvaluator();
$evaluator->evaluate(
    ['param' => ['eq' => 42]], 
    ['param' => 42]
);
```

## Expression language

Expression may be represented as array or JSON string:

```php
<?php

$expression = '{"field": {"eq": 42}}';
$expression = ["field" => ["eq" => 42]];
```

Expression consists of logical and comparison operations over nodes of some key-value structure:

```php
<?php

$evaluator = new ExpressionEvaluator();
$result = $evaluator->evaluate(
    // expression
    [
        'param1' => [
            'eq' => 42,
        ],
        'param2' => [
            'eq' => 43,
        ]
    ], 
    // key-value structure
    [
        'param1' => 42,
        'param2' => 43,
    ]
);

// will return true
```

Expression may be compound:

```php
<?php

$evaluator = new ExpressionEvaluator();
$evaluator->evaluate(
    // expression 
    ['param' => ['gt' => 10, 'lt' => 30]],
    // key-value struct 
    ['param' => 20]
);

// will return true
```

## Comparison expression

| Name | Description            |
|------|------------------------|
| eq   | Equals                 |
| neq  | Not equals             |
| lt   | Less then              |
| lte  | Less then or equals    |
| gt   | Greater then           |
| gte  | Greater than or equals |
| in   | In array               |
| nin  | Not in array           |

### Equals

```json
{"someField": {"eq": 42}}
```

or shorthand

```json
{"someField":  42}
```

### Not Equals

```json
{"someField": {"neq": 42}}
```

### Greater than

```json
{"someField": {"gt": 42}}
```

If comparing greater or equals:

```json
{"someField": {"gte": 42}}
```


### Less than

```json
{"someField": {"lt": 42}}
```

If comparing less or equals:

```json
{"someField": {"lte": 42}}
```

### In array

```json
{"someField": {"in": ["UKR", "USA"]}}
```

### Not in array

```json
{"someField": {"nin": ["UKR", "USA"]}}
```
