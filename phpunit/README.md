# Part 2 — Unit Testing (PHP + PHPUnit)

## Installation
composer.json:
```json
{
  "name": "acme/testing-demo",
  "require": {},
  "require-dev": {
    "phpunit/phpunit": "^9.6",
    "phpstan/phpstan": "^1.12"
  },
  "autoload": { "psr-4": { "Acme\": "src/" } },
  "autoload-dev": { "psr-4": { "Acme\Tests\": "tests/" } },
  "scripts": {
    "test": "phpunit --configuration tests/phpunit.xml",
    "test:coverage": "XDEBUG_MODE=coverage phpunit --configuration tests/phpunit.xml --coverage-text --coverage-html build/coverage"
  }
}
```
Install:
```bash
cd phpunit
composer install
```

## PHPUnit Config
tests/phpunit.xml:
```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit bootstrap="tests/bootstrap.php"
         colors="true"
         cacheResultFile=".phpunit.result.cache"
         beStrictAboutTestsThatDoNotTestAnything="true"
         beStrictAboutOutputDuringTests="true">
  <testsuites>
    <testsuite name="Unit Tests">
      <directory>tests</directory>
    </testsuite>
  </testsuites>
  <coverage processUncoveredFiles="true">
    <include><directory suffix=".php">src</directory></include>
    <report>
      <text outputFile="php://stdout"/>
      <html outputDirectory="build/coverage"/>
    </report>
  </coverage>
  <php>
    <env name="APP_ENV" value="test"/>
  </php>
</phpunit>
```

## Code Under Test
src/Calculator.php:
```php
<?php
declare(strict_types=1);
namespace Acme;

final class Calculator {
    public function add(int|float $a, int|float $b): int|float { return $a + $b; }
    public function divide(float $a, float $b): float {
        if ($b == 0.0) throw new \InvalidArgumentException('Division by zero.');
        return $a / $b;
    }
}
```

## Basic Test
tests/CalculatorTest.php:
```php
<?php
declare(strict_types=1);
namespace Acme\Tests;

use Acme\Calculator;
use PHPUnit\Framework\TestCase;

final class CalculatorTest extends TestCase {
    private Calculator $calc;
    protected function setUp(): void { $this->calc = new Calculator(); }

    public function testAdd(): void {
        $this->assertSame(7, $this->calc->add(3, 4));
    }

    public function testDivide(): void {
        $this->assertEqualsWithDelta(2.5, $this->calc->divide(5, 2), 1e-9);
    }

    public function testDivideByZeroThrows(): void {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Division by zero.');
        $this->calc->divide(1, 0);
    }
}
```

Run:
```bash
composer test
```

## Function Test
src/math.php:
```php
<?php
declare(strict_types=1);
namespace Acme;
function hypotenuse(float $a, float $b): float { return sqrt($a*$a + $b*$b); }
```

## Data Provider Example
```php
public function addProvider(): array {
    return [
      'ints' => [2, 3, 5],
      'floats' => [2.5, 0.5, 3.0],
      'negatives' => [-2, -3, -5],
    ];
}
/** @dataProvider addProvider */
public function testAddDataDriven($a, $b, $expected): void {
    $this->assertSame($expected, $this->calc->add($a, $b));
}
```

## Coverage
```bash
XDEBUG_MODE=coverage vendor/bin/phpunit --coverage-text --coverage-html build/coverage
```

## VS Code Extensions
- PHP Intelephense
- PHP Debug
- PHPUnit Test Explorer
- GitHub Copilot + Chat
