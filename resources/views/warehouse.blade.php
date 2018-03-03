@extends('layouts.app')
@section('asset')
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="{{asset('bootstrap/datetimepicker/bootstrap-datetimepicker.min.css')}}">
@endsection
@section('content')
    @include('warehouse.soldModal')
    <div class="row">
        <div class="container">
            <div class="row bottom-s">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#warehouse">目前庫存量</a></li>
                    <li><a data-toggle="tab" href="#new">新增庫存</a></li>
                    <li><a data-toggle="tab" href="#record">交易紀錄</a></li>
                </ul>
            </div>

            <div class="row">
                <div class="tab-content">
                    @include('warehouse.warehouse')
                    @include('warehouse.buy')
                    @include('warehouse.dealRecord')
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{mix('/js/pages/warehouse.js')}}"></script>
    {{--bootstrap datetimepicker--}}
    <script type="text/javascript" src="{{asset('bootstrap/datetimepicker/bootstrap-datetimepicker.js')}}"
            charset="UTF-8"></script>
    <script type="text/javascript" src="{{asset('bootstrap/datetimepicker/bootstrap-datetimepicker.zh-TW.js')}}"
            charset="UTF-8"></script>

    <script>
        $('.form_date').datetimepicker({
            language: 'zh-TW',
            weekStart: 1,
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 2,
            forceParse: 0,
            format: 'yyyy-mm-dd'
        });
    </script>
@endsection