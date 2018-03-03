@extends('layouts.app')

@section('content')
    <div class="row" v-if="loadingGif">
        <div class="text-center">
            <img src="{{asset('loading.gif')}}" alt="wait" class="rounded float-right">
        </div>
    </div>
    <div class="row" v-bind:class="{'invisible' : loadingGif }">
        <div class="col-12">
            <form class="form-horizontal">
                <div class="form-group">
                    <label for="wave-point" class="col-sm-2 control-label">下降點價位： </label>
                    <div class="col-sm-9">
                        <input type="number" class="form-control" name="wave-point"
                               id="wave-point" value="{{$wavePoint}}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="ms-percent" class="col-sm-2 control-label">中間點張數(%)：</label>
                    <div class="col-sm-9">
                        <input type="number" class="form-control" name="ms-percent" id="ms-percent"
                               value="{{$msPercent}}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="money" class="col-sm-2 control-label">總運用資金(萬元)：</label>
                    <div class="col-sm-9">
                        <input type="number" class="form-control" name="money" id="money" value="{{$money}}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="waveMoney" class="col-sm-2 control-label">波峰最大差價：</label>
                    <div class="col-sm-9">
                        <input type="number" class="form-control" name="wave-money" id="wave-money"
                               value="{{$waveMoney}}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="money" class="col-sm-2 control-label"></label>
                    <div class="col-sm-9">
                        <button type="button" class="btn btn-success" v-on:click="getPipe"> 送出</button>
                    </div>
                </div>
            </form>
            <div v-if="buyPointInfo.title != null">
                <div id="buyPointInfo">
                    <e-table :title="buyPointInfo.title" :rows="buyPointInfo.rows"></e-table>
                </div>
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#whilePoint"> @{{whilePoint.title}}</a></li>
                    <li><a data-toggle="tab" href="#upPipe"> @{{upPipe.title}}</a></li>
                    <li><a data-toggle="tab" href="#downPipe"> @{{downPipe.title}}</a></li>
                </ul>
                <div class="tab-content">
                    <div id="whilePoint" class="tab-pane fade  in active">
                        <e-table :title="whilePoint.title" :rows="whilePoint.rows"></e-table>
                        <short-strategy :title="whilePoint.title" :strategies="whilePoint.strategies"></short-strategy>
                    </div>
                    <div id="upPipe" class="tab-pane fade">
                        <e-table :title="upPipe.title" :rows="upPipe.rows"></e-table>
                        <short-strategy :title="upPipe.title" :strategies="upPipe.strategies"></short-strategy>
                    </div>
                    <div id="downPipe" class="tab-pane fade">
                        <e-table :title="downPipe.title" :rows="downPipe.rows"></e-table>
                        <short-strategy :title="downPipe.title" :strategies="downPipe.strategies"></short-strategy>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input id="ajaxAnalysisUrl" type="hidden" value="{{route('analysis.short.analysis')}}">
@endsection
@section('script')
    <script src="{{mix('/js/pages/analysisShort.js')}}"></script>
@endsection