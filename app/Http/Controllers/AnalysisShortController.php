<?php

namespace App\Http\Controllers;

use App\Formatters\Success;
use App\Repositories\Caches\AnalysisShortRepository as CacheRepository;
use App\Services\AnalysisShortService;

class AnalysisShortController extends Controller
{
    private $service;
    private $cacheRepository;

    public function __construct(AnalysisShortService $service,
                                CacheRepository $cacheRepository)
    {
        $this->service = $service;
        $this->cacheRepository = $cacheRepository;
    }

    public function show()
    {
        $beforeShortValuation = $this->cacheRepository->getBeforeShortAnalysis(auth()->user()->id);
        $wavePoint = $beforeShortValuation['wavePoint'] ?? 22;
        $msPercent = $beforeShortValuation['msPercent'] ?? 20;
        $money = $beforeShortValuation['money'] ?? 10;
        $waveMoney = $beforeShortValuation['waveMoney'] ?? 6;
        $warehouseMoneyDistance = $beforeShortValuation['warehouseMoneyDistance'] ?? 0.1;

        return view('analysis-short',
            compact(
                'wavePoint',
                'msPercent',
                'money',
                'waveMoney',
                'warehouseMoneyDistance'
            )
        );
    }

    /**
     * 短期分析 ajax
     *
     * @param Success $success
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function analysis(Success $success)
    {
        $wavePoint = request('wavePoint');
        $msPercent = request('msPercent') / 100;
        $money = request('money') * 10000;
        $waveMoney = request('waveMoney');

        $result = $this->service->analysis(
            $wavePoint,
            $msPercent,
            $money,
            $waveMoney
        );

        $this->saveRequestValueToCache(auth()->user()->id);

        return response()->json(
            $success->format(
                request(),
                $result
            )
        );
    }

    protected function saveRequestValueToCache(int $userId)
    {
        $this->cacheRepository->saveShortAnalysis(
            $userId,
            [
                'wavePoint'              => request('wavePoint'),
                'msPercent'              => request('msPercent'),
                'money'                  => request('money'),
                'waveMoney'              => request('waveMoney'),
            ]
        );
    }
}
