@extends('layouts.app')

@section('title', $user->name . ' 的个人中心')

@section('content')

<div class="row">

    <div class="col-lg-3 col-md-3 hidden-sm hidden-xs user-info">
        <div class="card ">
            <img class="card-img-top" src="https://raw.githubusercontent.com/qianyewoailuo/qianyewoailuo.github.io/master/comic/%E5%A4%B4%E5%83%8F.jpg" alt="{{ $user->name }}">
            <div class="card-body">
                <h5><strong>个人简介</strong></h5>
                <hr>
                <p> {{ $user->introduction }} </p>
                <hr>
                <h5><strong>注册于</strong></h5>
                <!-- 易于人类阅读 例如1年前之类 -->
                <!-- 注意还要去app/provider中设置carnon本地中文化 -->
                <hr>
                <p>{{$user->created_at}}</p>
                <hr>
                <p>{{$user->created_at->diffForHumans()}}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
        <div class="card ">
            <div class="card-body">
                <h1 class="mb-0" style="font-size:22px;">{{ $user->name }} <small>{{ $user->email }}</small></h1>
            </div>
        </div>
        <hr>

        {{-- 用户发布的内容 --}}
        <div class="card ">
            <div class="card-body">
                暂无数据 ~_~
            </div>
        </div>

    </div>
</div>
@stop