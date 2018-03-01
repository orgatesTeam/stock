<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>財富風險管理</title>

    <link rel="shortcut icon" href="{{asset('warehouse.png')}}"/>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/my-style.css')}}">
    @yield('asset')
</head>

<body>
<div class="container">
    <div class="row">
       @include('layouts.alert')
    </div>
    <div class="row bottom-s">
        @if(auth()->user())
            <nav class="navbar navbar-inverse">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        @if(Agent::isMobile())
                            <a class="navbar-brand" href="#">
                                @foreach(config('menus') as $menu)
                                    @if($menu['routeAs'] == request()->route()->getName() )
                                        {{$menu['name']}}
                                    @endif
                                @endforeach
                            </a>
                        @endif
                    </div>
                    <div class="collapse navbar-collapse" id="myNavbar">
                        <ul class="nav navbar-nav">
                            @foreach(config('menus') as $menu)
                                <li><a href="{{route($menu['routeAs'])}}">{{$menu['name']}}</a></li>
                            @endforeach
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li>
                                <ul class="nav top-menu">
                                    <li class="dropdown">
                                        <a data-toggle="dropdown" class="dropdown-toggle" href="#" aria-expanded="false">
                                            <img style="border-radius: 50%;" width="28"
                                                 src="{{auth()->user()->avatar}}">
                                            <span class="username"> 系統管理員</span>
                                            <b class="caret"></b>
                                        </a>
                                        <ul class="dropdown-menu extended logout">
                                            <form action="{{route('logout')}}" method="post">
                                                {{csrf_field()}}
                                                <input type="submit" value="登出" class="btn btn-default">
                                                <span class="glyphicon glyphicon-log-in"></span>
                                            </form>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        @endif
    </div>
    <div class="container">
        <div id="app">
            @yield('content')
        </div>
    </div>
</div>
</body>
</html>
@yield('script')
