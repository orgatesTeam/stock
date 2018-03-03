<?php

namespace App\Services;

use App\Repositories\AnalysisShortRepository;
use Yish\Generators\Foundation\Service\Service;

class AnalysisShortService extends Service
{
    protected $repository;

    public function __construct(AnalysisShortRepository $analysisShortRepository)
    {
        $this->repository = $analysisShortRepository;
    }

    public function analysis($wavePoint, $msPercent, $money, $waveMoney)
    {
        $analysisShortRepository = $this->repository;

        $upPipe = $analysisShortRepository->upPipe($wavePoint, $waveMoney);
        $downPipe = $analysisShortRepository->downPipe($wavePoint, $waveMoney);
        $whilePoint = $analysisShortRepository->whilePoint($wavePoint, $waveMoney);
        $buyPointInfo = $analysisShortRepository->buyPoint($msPercent, $whilePoint, $money);
        $upPipeStrategies = $analysisShortRepository->warehouseStrategies(
            $buyPointInfo['buySheets'],
            $upPipe
        );
        $downPipeStrategies = $analysisShortRepository->warehouseStrategies(
            $buyPointInfo['buySheets'],
            $downPipe
        );
        $whilePipeStrategies = $analysisShortRepository->warehouseStrategies(
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
