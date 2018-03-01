<?php

namespace App\Http\Controllers;

use App\Formatters\Success;
use App\Repositories\Caches\AnalysisRepository as CacheRepository;
use App\Services\AnalysisService;
use Illuminate\Http\Request;

class AnalysisController extends Controller
{
    private $service;
    private $cacheRepository;

    public function __construct(AnalysisService $service,
                                CacheRepository $cacheRepository)
    {
        $this->service = $service;
        $this->cacheRepository = $cacheRepository;
    }

    public function shortShow()
    {
        $beforeShortValuation = $this->cacheRepository->getBeforeShortAnalysis(auth()->user()->id);

        $wavePoint = $beforeShortValuation['wavePoint'] ?? 22;
        $msPercent = $beforeShortValuation['msPercent'] ?? 20;
        $money = $beforeShortValuation['money'] ?? 10;
        $waveMoney = $beforeShortValuation['waveMoney'] ?? 6;

        return view('analysis-short',
            compact(
                'wavePoint',
                'msPercent',
                'money',
                'waveMoney'
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
    public function shortValuation(Success $success)
    {
        $wavePoint = request('wavePoint');
        $msPercent = request('msPercent') / 100;
        $money = request('money') * 10000;
        $waveMoney = request('waveMoney');

        $result = $this->service->shortAnalysisData(
            $wavePoint,
            $msPercent,
            $money,
            $waveMoney
        );

        $this->storeShortValuation(auth()->user()->id, $result);

        return response()->json(
            $success->format(
                request(),
                $result
            )
        );
    }

    protected function storeShortValuation(int $userId, $result)
    {
        $this->cacheRepository->saveShortAnalysis(
            $userId,
            [
                'wavePoint' => request('wavePoint'),
                'msPercent' => request('msPercent'),
                'money'     => request('money'),
                'waveMoney' => request('waveMoney')
            ]
        );
    }
}
