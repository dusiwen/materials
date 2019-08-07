@extends('Layout.index')
@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="/AdminLTE/bower_components/select2/dist/css/select2.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/AdminLTE/dist/css/AdminLTE.min.css">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="/AdminLTE/plugins/iCheck/all.css">
@endsection
@section('content')
    <section class="content">
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">保存台账</h3>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right"></div>
            </div>
            <br>
            <form class="form-horizontal" id="frmUpdate">
                <div class="form-group">
                    <label class="col-sm-3 control-label">名称：</label>
                    <div class="col-sm-10 col-md-8">
                        <input placeholder="名称" class="form-control input-lg" type="text" autofocus onkeydown="if(event.keyCode==13){return false;}" required
                               name="name" value="{{$maintain->name}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">统一标识代码：</label>
                    <div class="col-sm-10 col-md-8">
                        <input placeholder="统一标识代码" class="form-control input-lg" type="text" onkeydown="if(event.keyCode==13){return false;}"
                               name="unique_code" value="{{$maintain->unique_code}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">父级：</label>
                    <div class="col-sm-10 col-md-8">
                        <select name="parent_unique_code" class="form-control select2 input-lg" style="width:100%;">
                            <option value="">顶级</option>
                            @foreach($maintains as $item)
                                <option value="{{$item->unique_code}}" {{$maintain->parent_unique_code == $item->unique_code ? 'selected' : ''}}>{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="box-footer">
                    <a href="{{url('maintain')}}?page={{request()->get('page',1)}}" class="btn btn-default pull-left btn-lg btn-flat"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
                    <a href="javascript:" onclick="fnUpdate()" class="btn btn-warning pull-right btn-lg btn-flat"><i class="fa fa-check">&nbsp;</i>保存</a>
                </div>
            </form>
        </div>
    </section>
@endsection
@section('script')
    <script src="/AdminLTE/bower_components/select2/dist/js/select2.full.min.js"></script>
    <!-- iCheck 1.0.1 -->
    <script src="/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <script>
        $(function () {
            $('.select2').select2();
            // iCheck for checkbox and radio inputs
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });
        });

        /**
         * 保存
         */
        fnUpdate = function () {
            $.ajax({
                url: "{{url('maintain',$maintain->unique_code)}}",
                type: "put",
                data: $("#frmUpdate").serialize(),
                success: function (response) {
                    console.log('success:', response);
                    alert(response);
                    location.href="{{url('maintain')}}?page{{request()->get('page',1)}}";
                },
                error: function (error) {
                    // console.log('fail:', error);
                    if (error.responseText == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                }
            });
        };
    </script>
@endsection
