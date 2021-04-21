<?php

namespace tests;

use PHPUnit\Framework\TestCase;
use toreti\trader\rsi\CutlersRSI;

class CutlersRSITest extends TestCase
{
    /**
     * @dataProvider pricesProvider
     */
    public function testCalculate($period, $precision, $prices, $expectedRSI)
    {
        $rsi = (new CutlersRSI())->calculate($prices, $period, $precision);
        $this->assertEquals($expectedRSI, $rsi);
    }

    public function pricesProvider()
    {
        // most recent prices first
        return [
            'sufficient data' => [
                'period' => 14,
                'precision' => 2,
                'prices' => [
                    4834.91,// current
                    4724.89,// previous
                    4555.14,
                    4587.48,
                    4386.69,
                    4310.01,
                    4337.44,
                    4280.68,
                    4316.01,
                    4114.01,
                    4040.0,
                    4016.0,
                    4086.29,
                    4139.98,
                    4108.37,
                    4285.08,
                ],
                'expected RSI' => [
                    81.19,// current
                    67.86,// previous
                    62.72,
                ],
            ],
            'insufficient data' => [
                'period' => 14,
                'precision' => 2,
                'prices' => [
                    4834.91,// current
                    4724.89,// previous
                ],
                'expected RSI' => [],
            ],
        ];
    }
}
