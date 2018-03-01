<?php

namespace App\Services;

use App\Repositories\AnalysisShortRepository;
use Yish\Generators\Foundation\Service\Service;

class AnalysisService extends Service
{
    protected $repository;

    public function shortAnalysisData($wavePoint, $msPercent, $money, $waveMoney)
    {
        $analysisShortRepository = app(AnalysisShortRepository::class);

        $upPipe = $analysisShortRepository->upPipe($wavePoint, $waveMoney);
        $downPipe = $analysisShortRepository->downPipe($wavePoint, $waveMoney);
        $whilePoint = $analysisShortRepository->whilePoint($wavePoint, $waveMoney);
        $buyPointInfo = $analysisShortRepository->buyPoint($msPercent, $whilePoint, $money);
        $upPipeStrategies = $this->warehouseStrategies(
            $buyPointInfo['buySheets'],
            $upPipe
        );
        $downPipeStrategies = $this->warehouseStrategies(
            $buyPointInfo['buySheets'],
            $downPipe
        );
        $whilePipeStrategies = $this->warehouseStrategies(
            $buyPointInfo['buySheets'],
            $whilePoint
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
     */
    protected function warehouseStrategies($sheetTotal, $pipe)
    {
        $allocateSheets = $this->allocateSheets($sheetTotal);
        $time = count($allocateSheets);
        $distanceMoney = $pipe['middle'] - $pipe['bottom'];
        $unitMoney = $this->ceil_dec($distanceMoney / $time, 1);
        $result = [];
        $money = $pipe['middle'];

        // 中點以下
        foreach ($allocateSheets as $sheet) {
            $result[] = [
                'money' => round($money, 2),
                'sheet' => $sheet
            ];
            $money = $money - $unitMoney;
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
