<?php

namespace App\Tests\Entity;

use App\Entity\Measurement;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertSame;
use PHPUnit\Framework\Attributes\DataProvider;

class MeasurementTest extends TestCase
{

    #[DataProvider('dataGetFahrenheit')]
    public function testGetFahrenheit(string $celsius, string $expectedFahrenheit): void
    {
        $measurement = new Measurement();
        $measurement->setCelsius($celsius);

        self::assertEqualsWithDelta(
            (float) $expectedFahrenheit,
            $measurement->getFahrenheit(),
            0.000001,
        );
    }

    public static function dataGetFahrenheit(): array
    {
        return [
            ['0', '32'],
            ['-100', '-148'],
            ['100', '212'],
            ['-40', '-40'],
            ['0.5', '32.9'],
            ['-0.5', '31.1'],
            ['-10.25', '13.55'],
            ['12', '53.6'],
            ['15', '59'],
            ['22', '71.6'],
        ];
    }

}
