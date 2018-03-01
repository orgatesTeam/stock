<?php

namespace App\Services;

use App\Repositories\AnalysisShortRepository;
use Yish\Generators\Foundation\Service\Service;

class AnalysisService extends Service
{
    protected $repository;

    public function shortAnalysisData($wavePoint, $msPercent, $money, $waveMoney, $warehouseMoneyDistance)
    {
        $analysisShortRepository = app(AnalysisShortRepository::class);

        $upPipe = $analysisShortRepository->upPipe($wavePoint, $waveMoney);
        $downPipe = $analysisShortRepository->downPipe($wavePoint, $waveMoney);
        $whilePoint = $analysisShortRepository->whilePoint($wavePoint, $waveMoney);
        $buyPointInfo = $analysisShortRepository->buyPoint($msPercent, $whilePoint, $money);
        $upPipeStrategies = $this->warehouseStrategies(
            $buyPointInfo['buySheets'],
            $upPipe,
            $warehouseMoneyDistance
        );
        $downPipeStrategies = $this->warehouseStrategies(
            $buyPointInfo['buySheets'],
            $downPipe,
            $warehouseMoneyDistance
        );
        $whilePipeStrategies = $this->warehouseStrategies(
            $buyPointInfo['buySheets'],
            $whilePoint,
            $warehouseMoneyDistance
        );

        return $this->formatterShortAnalysis(
            $upPipe,
            $downPipe,
            $whilePoint,
            $buyPointInfo,
            $upPipeStrategies,
            $downPipeStrategies,
            $whilePipeStrategies
        );
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
    protected function warehouseStrategies($sheetTotal, $pipe, $warehouseMoneyDistance)
    {
        //除法是獲得區塊，但為了獲得數字點需要減 1
        $sheetTotal = $sheetTotal - 1;
        $middleMoney = $pipe['middle'];
        $bottomMoney = $pipe['bottom'];
        $distanceMoney = $middleMoney - $bottomMoney;

        if ($sheetTotal <= 0) {
            return [0 => [
                'money' => $middleMoney,
                'sheet' => 0
            ]];
        };


        $everySheet = 1;
        while ($distanceMoney / ($sheetTotal / $everySheet) <= $warehouseMoneyDistance) {
            $everySheet++;
            if ($everySheet > 100) {
                break;
            }
        }

        $maxForeachTime = intval($sheetTotal / $everySheet);
        $everySheet = intval($sheetTotal / $maxForeachTime);
        $everyMoney = $distanceMoney / (($sheetTotal / $everySheet)+1);
        $money = $middleMoney;
        foreach (range(1, $maxForeachTime) as $index) {
            $result[] = [
                'money' => round($money, 2),
                'sheet' => $everySheet
            ];
            $money = $money - $everyMoney;
        }

        return $result;
    }

    //無條件進位
    protected function ceil_dec($v, $precision)
    {
        $c = pow(10, $precision);
        return ceil($v * $c) / $c;
    }

    protected function allocateSheets(int $sheetTotal)
    {
        $everyTimeAddOne = 5;
        $add = 1;
        $time = 0;
        $result = [];

        // 1+1+1+1+1+2+2+2.... 每五次增加一單位
        while ($sheetTotal >= 1) {

            if ($time++ >= $everyTimeAddOne) {
                $add++;
                $time = 0;
            }

            $result[] = $add;
            $sheetTotal -= $add;
        }

        return $result;
    }

    protected function formatterShortAnalysis($upPipe, $downPipe, $whilePoint, $buyPointInfo, $upPipeStrategies,
                                              $downPipeStrategies, $whilePipeStrategies)
    {
        return [
            'upPipe'       => [
                'title'      => '上煙斗',
                'rows'       => [
                    '高點' => $upPipe['top'],
                    '中點' => $upPipe['middle'],
                    '低點' => $upPipe['bottom']
                ],
                'strategies' => $upPipeStrategies
            ],
            'downPipe'     => [
                'title'      => '下煙斗',
                'rows'       => [
                    '高點' => $downPipe['top'],
                    '中點' => $downPipe['middle'],
                    '低點' => $downPipe['bottom']
                ],
                'strategies' => $downPipeStrategies
            ],
            'whilePoint'   => [
                'title'      => '綜合點位',
                'rows'       => [
                    '高點' => $whilePoint['top'],
                    '中點' => $whilePoint['middle'],
                    '低點' => $whilePoint['bottom']
                ],
                'strategies' => $whilePipeStrategies
            ],
            'buyPointInfo' => [
                'title' => '安全買點分析',
                'rows'  => [
                    '安全中間點運用資金比重'   => $buyPointInfo['percent'],
                    '平均張數價格 ( 融資 )' => $buyPointInfo['avgSheets'],
                    '可運用資金'         => $buyPointInfo['useMoney'],
                    '可買張數'          => $buyPointInfo['buySheets']
                ]
            ],
        ];
    }
}
