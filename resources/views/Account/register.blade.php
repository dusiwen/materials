@extends('Layout.login')
@section('title')
    注册
@endsection
@section('content')
    <div class="register-box">
        <div class="register-logo" style="font-size: 28px;">
            <a href="{{url('/')}}"><b>电务检修基地</b>&nbsp;管理系统</a>
        </div>

        <div class="register-box-body">
            <p class="login-box-msg">注册</p>
            @include('Layout.alert')
            <form action="{{url('/register')}}" method="post">
                {{csrf_field()}}
                <div class="form-group has-feedback">
                    <input name="account" type="text" class="form-control" placeholder="账号" value="{{old('account','jericho1')}}" required autofocus>
                    <span class="fa fa-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input name="nickname" type="text" class="form-control" placeholder="姓名" value="{{old('nickname','JerichoPH1')}}" required>
                    <span class="fa fa-etsy form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input
                        name="phone"
                        type="text"
                        class="form-control"
                        placeholder="手机号"
                        value="{{old('phone','13522178057')}}"
                        required>
                    <span class="fa fa-phone form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input
                        name="email"
                        type="email"
                        class="form-control"
                        placeholder="邮箱"
                        value="{{old('email','jericho_ph@qq.com')}}"
                        required>
                    <span class="fa fa-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input
                        name="password"
                        type="password"
                        class="form-control"
                        placeholder="密码"
                        required>
                    <span class="fa fa-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        {{--<div class="checkbox icheck">--}}
                        {{--<label>--}}
                        {{--<input type="checkbox">&nbsp;&nbsp;同意<a href="#">注册协议</a>--}}
                        {{--</label>--}}
                        {{--</div>--}}
                        <a href="{{url('/login')}}" class="text-center">已有账号，去登陆</a>
                    </div>
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">&nbsp;&nbsp;注&nbsp;&nbsp;册&nbsp;&nbsp;</button>
                    </div>
                </div>
            </form>

            {{--<div class="social-auth-links text-center">--}}
            {{--<p>- OR -</p>--}}
            {{--<a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign up using--}}
            {{--Facebook</a>--}}
            {{--<a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign up using--}}
            {{--Google+</a>--}}
            {{--</div>--}}

        </div>
        <!-- /.form-box -->
    </div>
@endsection
