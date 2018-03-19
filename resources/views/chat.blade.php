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
                    &nbsp;在線名單
                </div>
                <div class="panel-body list-body">
                    <div class="col-md-12">
                        <div v-for="user in users" class="col-md-4">

                            <a :href="facebookLink(user)" target="_blank">
                                <img style="border-radius: 50%;width:40px"
                                     :src="facebookUserImg(user)" alt="">
                            </a>
                        </div>
                    </div>

                </div>
                <div class="panel-footer" id="list-count">當前在線：@{{ onlineCount }}人</div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{mix('/js/pages/chat.js')}}"></script>
@endsection