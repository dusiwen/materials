@extends('Layout.login')
@section('title')
    登陆
@endsection
@section('content')
    <div class="login-box">
        <div class="login-logo" style="font-size: 28px;">
            <a href="{{url('/')}}"><b>智能仓储</b>&nbsp;管理系统</a>
        </div>

        <div class="login-box-body">
            <p class="login-box-msg">登陆</p>
            @include('Layout.alert')
            <form action="{{url('/login')}}" method="post">
                {{csrf_field()}}
                <div class="form-group has-feedback">
                    <input name="account" type="text" class="form-control" placeholder="账号" value="{{old('account','admin')}}" required autofocus autocomplete>
                    <span class="form-control-feedback fa fa-envelope"></span>
                </div>
                <div class="form-group has-feedback">
                    <input name="password" type="password" class="form-control" placeholder="密码" required value="123123">
                    <span class="fa fa-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <a href="{{url('/forget')}}">忘记密码？</a><br>
                        <a href="{{url('/register')}}" class="text-center">没有账号，去注册</a>
                        {{--<div class="checkbox icheck">--}}
                        {{--<label>--}}
                        {{--<input type="checkbox"> Remember Me--}}
                        {{--</label>--}}
                        {{--</div>--}}
                    </div>
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">&nbsp;&nbsp;登&nbsp;&nbsp;陆&nbsp;&nbsp;</button>
                    </div>
                </div>
            </form>

            {{--<div class="social-auth-links text-center">--}}
            {{--<p>- OR -</p>--}}
            {{--<a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using--}}
            {{--Facebook</a>--}}
            {{--<a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using--}}
            {{--Google+</a>--}}
            {{--</div>--}}

        </div>
        <!-- /.login-box-body -->
    </div>
@endsection
