@extends('layouts.app')
@section('asset')
@endsection
@section('content')
    <div class="row" style="margin-top:15px;">

        <!-- 在線人數 -->
        <div class="col-sm-4" v-if="showUser">
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

        <!-- 聊天区 -->
        <div :class="showUser?'col-md-8':'col-md-12'">
            <!-- 聊天内容 -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-earphone"></span>
                    &nbsp;聊天内容
                </div>
                <div class="panel-body chat-body">
                    <div id="messageBox" class="msg-list-body" style="width:100%;height:300px;overflow:auto;">
                        <div v-for="chatItem in chatItems">
                            <div class="clearfix msg-wrap">
                                <div :class="isSelf(chatItem) ? 'pull-right' : 'pull-left'">

                                    <div class="col-md-12">
                                        <div class="row">
                                            <div :class="isSelf(chatItem) ? 'hidden' : 'pull-left'">
                                                <a :href="facebookLink(chatItem.userID)" target="_blank">
                                                    <img style="border-radius: 50%;width:30px"
                                                         :src="facebookUserImg(chatItem.userID)" alt="">
                                                </a>
                                            </div>

                                            <div class="chat-circle" :class="isSelf(chatItem) ? 'chat-self' : 'chat-other'">
                                                <div class="chat">@{{ chatItem.content }}</div>
                                            </div>
                                        </div>

                                        <div class="row">
                                         <span class="chat-time"
                                               :class="isSelf(chatItem) ? 'pull-right' : 'pull-left'"><span
                                                     class="glyphicon glyphicon-time"></span>@{{ chatItem.datetime }}</span>
                                        </div>
                                    </div>


                                </div>

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
                    <button class="btn btn-default" type="button" v-on:click="changeShowUser()">
                        @{{ showUser?'隱藏在線人數':'顯示在線人數' }}
                        <span class="glyphicon glyphicon-user"></span>
                    </button>
                </span>
            </div>
        </div>

    </div>
@endsection
@section('script')
    <script src="{{mix('/js/pages/chat.js')}}"></script>
@endsection