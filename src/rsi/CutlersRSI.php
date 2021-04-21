<?php

namespace toreti\trader\rsi;

class CutlersRSI
{
    /**
     * Cutler's RSI is calculated by determining the difference between the previous bar's close and the current close.
     *
     * @param array $prices prices to calculate RSI, pass most recent prices first
     * @param int $period number of periods to calculate RSI
     * @param int $precision number of decimal digits to round RSI values
     * @return array RSI series based on informed prices
     */
    public function calculate(array $prices, int $period = 14, int $precision = 4): array
    {
        $sma = function ($prices, $period) {
            return array_sum($prices) / $period;
        };
        $rsi = function ($prices, $period, $precision) use ($sma) {
            $gain = [];
            $loss = [];
            foreach ($prices as $key => $currentClose) {
                if (!isset($prices[$key + 1])) {
                    continue;
                }
                $previousClose = $prices[$key + 1];
                if ($currentClose > $previousClose) {
                    $gain[] = $currentClose - $previousClose;
                    $loss[] = 0;
                } else {
                    $gain[] = 0;
                    $loss[] = $previousClose - $currentClose;
                }
            }
            $avgGain = $sma($gain, $period);
            $avgLoss = $sma($loss, $period);
            $rs = $avgGain / $avgLoss;
            $rsi = 100 - (100 / (1 + $rs));
            return round($rsi, $precision);
        };
        $series = [];
        $lastKeyToCalculateRSI = count($prices) - $period;
        foreach ($prices as $key => $close) {
            if ($key > $lastKeyToCalculateRSI) {
                continue;
            }
            $pricesToCalculateRSI = array_slice($prices, $key, $period + 1);
            $series[] = $rsi($pricesToCalculateRSI, $period, $precision);
        }
        return $series;
    }
}
