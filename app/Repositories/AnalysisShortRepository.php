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
            'useMoney'  => $this->convertThousand($totalMoney),
            'buySheets' => $sheets
        ];
    }

    protected function convertThousand($number)
    {
        $newNumber = [];
        while (strlen($number) > 3) {
            $newNumber[] = substr($number, strlen($number) - 3, strlen($number) - 1);
            $number = substr($number, 0, strlen($number) - 3);
        }
        return $number . '.' . implode('.', array_reverse($newNumber));
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

    /**
     * 建倉策略
     *
     * @param $sheetTotal  總建倉張數
     * @param $pipe 煙斗屬性
     * @param $warehouseMoneyDistance 建倉區間價格
     *
     * @return array
     */
    public function warehouseStrategies($sheetTotal, $pipe)
    {
        //除法是獲得區塊，但為了獲得數字點需要減 1
        $sheetTotal = $sheetTotal - 1;
        $middleMoney = $pipe['middle'];
        $bottomMoney = $pipe['bottom'];
        $distanceMoney = $middleMoney - $bottomMoney;

        //例外處理
        if ($sheetTotal < 1) {
            return [0 => [
                'money' => $middleMoney,
                'sheet' => 0
            ]];
        };

        //0.1以上張數間距價格 一個間距多少張數
        $everySheet = 1;
        while ($distanceMoney / ($sheetTotal / $everySheet) <= 0.1 ) {
            $everySheet++;
            if ($everySheet > 100) {
                break;
            }
        }

        $maxForeachTime = intval($sheetTotal / $everySheet);
        $everyMoney = $distanceMoney / $maxForeachTime;
        $money = $middleMoney;
        foreach (range(0, $maxForeachTime) as $index) {
            $result[] = [
                'money' => round($money, 2),
                'sheet' => $everySheet
            ];
            $money = $money - $everyMoney;
        }

        return $result;
    }
}
