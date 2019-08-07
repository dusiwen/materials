@extends('Layout.login')
@section('title')
    忘记密码
@endsection
@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{url('/')}}"><b>I</b>ot</a>
        </div>

        <div class="login-box-body">
            <p class="login-box-msg">忘记密码</p>
            @include('Layout.alert')
            <form action="{{url('/forget')}}" method="post">
                {{csrf_field()}}
                {{method_field('PUT')}}
                <input type="hidden" name="type" value="email">
                <div class="form-group">
                    <div class="input-group">
                        <div class="form-group has-feedback">
                            <input
                                name="account"
                                type="text"
                                class="form-control"
                                placeholder="账号"
                                value="{{old('account')}}"
                                required>
                            <span class="form-control-feedback fa fa-envelope"></span>
                        </div>
                        <span class="input-group-addon" id="basic-addon2"><a href="javascript:" onclick="fnGetCode()">获取验证码</a></span>
                    </div>
                </div>
                <div class="form-group has-feedback">
                    <input
                        name="code"
                        type="text"
                        class="form-control"
                        placeholder="验证码"
                        required>
                    <span class="form-control-feedback fa fa-check"></span>
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
@section('script')
    <script>
        /**
         * 获取验证码
         */
        fnGetCode = function () {
            alert('测试验证码：0000');
        };
    </script>
@endsection
