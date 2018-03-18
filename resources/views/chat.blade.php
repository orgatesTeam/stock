@extends('layouts.app')
@section('asset')
@endsection
@section('content')
    <div class="row" style="margin-top:15px;">

        <!-- 聊天区 -->
        <div class="col-sm-8">
            <!-- 聊天内容 -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-earphone"></span>
                    &nbsp;聊天内容
                </div>
                <div class="panel-body chat-body">
                    <div class="msg-list-body" style="width:500px;height:300px;overflow:auto;">
                        <div v-for="chatItem in chatItems">
                            <div class="clearfix msg-wrap">
                                <div class="msg-head">
                                      <span class="msg-name label label-primary pull-left">
                                      <span class="glyphicon glyphicon-user"></span>
                                       &nbsp;李元傑
                                      </span><span class="msg-time label label-default pull-left">
                                      <span class="glyphicon glyphicon-time"></span>
                        &nbsp;@{{ chatItem.datetime }}
                        </span>
                                </div>
                                <div class="msg-content">@{{ chatItem.content }}</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- 输入框 -->
            <div class="input-group input-group-lg">
                <input type="text" class="form-control" placeholder="請輸入聊天內容" v-model="message">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button" v-on:click="send()">
                        發送
                        <span class="glyphicon glyphicon-send"></span>
                    </button>
                </span>
            </div>
        </div>

        <!-- 在线列表 -->
        <div class="col-sm-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-list"></span>
                    &nbsp;在线名单
                </div>
                <div class="panel-body list-body">
                    <table class="table table-hover list-table">
                        <!--<tr>-->
                        <!--<td>test</td>-->
                        <!--</tr>-->
                    </table>
                </div>
                <div class="panel-footer" id="list-count">当前在线：0人</div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{mix('/js/pages/chat.js')}}"></script>
@endsection