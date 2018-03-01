<?php

namespace App\Repositories;

use Yish\Generators\Foundation\Repository\Repository;

class AnalysisShortRepository extends Repository
{
    protected $model;

    public function buyPoint($msPercent, $whilePipe, $money)
    {
        // 買點金額比
        $percent = 1 - $msPercent;
        // 買點平均金額
        $avgMoney = $this->avgMsMoney($whilePipe['middle'], $whilePipe['bottom']);
        // 買點可用金額
        $totalMoney = $money * $percent;
        //買點張數
        $sheets = floor($totalMoney / $avgMoney);

        return [
            'percent'   => ($percent * 100) . ' % ',
            'avgSheets' => $avgMoney,
            'useMoney'  => ($totalMoney / 1000) . ' . ' . '000',
            'buySheets' => $sheets
        ];
    }

    //上煙斗
    public function upPipe(float $wavePoint, float $waveMoney)
    {
        return [
            'top'    => $wavePoint + ($waveMoney / 2),
            'middle' => $wavePoint,
            'bottom' => $wavePoint - ($waveMoney / 2)
        ];
    }

    //下煙斗
    public function downPipe(float $wavePoint, float $waveMoney)
    {
        return [
            'top'    => $wavePoint,
            'middle' => $wavePoint - ($waveMoney / 2),
            'bottom' => $wavePoint - ($waveMoney)
        ];
    }

    //綜合區段 買點
    public function whilePoint(float $wavePoint, float $waveMoney)
    {
        return [
            'top'    => $this->upPipe($wavePoint, $waveMoney)['top'],
            'middle' => ($this->upPipe($wavePoint, $waveMoney)['top'] + $this->downPipe($wavePoint, $waveMoney)['bottom']) / 2,
            'bottom' => $this->downPipe($wavePoint, $waveMoney)['bottom']
        ];
    }

    /**
     *  70 % 買點 ，每張 平均金額  ( 綜合中間點 + 綜合底點 ) / 2
     *
     * @param $whileMiddlePipe
     * @param $whileBottomPipe
     * @param bool $isFinancing 有融資
     *
     * @return float|int
     */
    public function avgMsMoney($whileMiddlePipe, $whileBottomPipe, $isFinancing = true)
    {
        $money = ($whileMiddlePipe + $whileBottomPipe) / 2 * 1000;

        if ($isFinancing) {
            return $money - (floor($money * 0.6 / 1000) * 1000);
        }

        return $money;
    }
}
