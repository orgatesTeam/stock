@extends('layouts.app')

@section('content-not-vue')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">

                    <div class="panel-heading">
                        <div class="col-md-offset-3">
                            <img src="{{asset('warehouse.png')}}" alt="財富管理" class="img-thumbnail">
                        </div>
                    </div>

                    <div class="panel-body" style="text-align: center">
                        <a href="{{route('facebook.login')}}" class="btn btn-primary">
                            Facebook Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection