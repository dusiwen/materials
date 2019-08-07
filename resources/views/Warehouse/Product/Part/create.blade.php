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
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">新建零件</h3>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right"></div>
            </div>
            <br>
            <div class="box-body">
                <form class="form-horizontal" id="frmCreate">
                    <input type="hidden" name="fix_cycle_type" value="YEAR">
                    <div class="form-group">
                        <label class="col-sm-3 control-label text-danger">名称*：</label>
                        <div class="col-sm-10 col-md-8">
                            <input class="form-control"
                                   name="name" type="text" placeholder="名称" value=""
                                   required autofocus onkeydown="if(event.keyCode==13){return false;}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">下标：</label>
                        <div class="col-sm-10 col-md-8">
                            <input class="form-control"
                                   name="subscript" type="text" placeholder="下标" value=""
                                   required onkeydown="if(event.keyCode==13){return false;}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label text-sm">所属设备型号：</label>
                        <div class="col-sm-10 col-md-8">
                            <select name="category_open_code" class="form-control select2" style="width:100%;">
                                @foreach($categories as $categoryOpenCode => $categoryName)
                                    <option value="{{$categoryOpenCode}}">{{$categoryName}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{--                    <div class="form-group">--}}
                    {{--                        <label class="col-sm-3 control-label">定期维修：</label>--}}
                    {{--                        <div class="col-sm-10 col-md-8">--}}
                    {{--                            <select name="parent_id" class="form-control select2" style="width:100%;">--}}
                    {{--                                @foreach($fixCycleTypes as $key=>$fixCycleType)--}}
                    {{--                                    <option value="{{$key}}">{{$fixCycleType}}</option>--}}
                    {{--                                @endforeach--}}
                    {{--                            </select>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                    {{--                    <div class="form-group">--}}
                    {{--                        <label class="col-sm-3 control-label text-sm">定期维修时间：</label>--}}
                    {{--                        <div class="col-sm-10 col-md-8">--}}
                    {{--                            <input class="form-control" type="text" required onkeydown="if(event.keyCode==13){return false;}"--}}
                    {{--                                   placeholder="定期维修时间" value="" name="subscript">--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                    {{--<div class="form-group">--}}
                    {{--<label class="col-sm-3 control-label">前缀名：</label>--}}
                    {{--<div class="col-sm-10 col-md-8">--}}
                    {{--<input class="form-control"--}}
                    {{--name="prefix_name" type="text" placeholder="前缀名" value=""--}}
                    {{--required onkeydown="if(event.keyCode==13){return false;}">--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                    {{--<label class="col-sm-3 control-label text-sm">前缀名下标：</label>--}}
                    {{--<div class="col-sm-10 col-md-8">--}}
                    {{--<input class="form-control"--}}
                    {{--name="prefix_subscript" type="text" placeholder="前缀名下标" value=""--}}
                    {{--required onkeydown="if(event.keyCode==13){return false;}">--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">特性：</label>
                        <div class="col-sm-10 col-md-8">
                            <input class="form-control"
                                   name="character" type="text" placeholder="特性" value=""
                                   required onkeydown="if(event.keyCode==13){return false;}">
                        </div>
                    </div>
                    <div class="box-footer">
                        <a href="{{url('warehouse/product/part')}}" class="btn btn-default pull-left"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
                        <a href="javascript:" onclick="fnCreate()" class="btn btn-success pull-right"><i class="fa fa-check">&nbsp;</i>新建</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script src="/AdminLTE/bower_components/select2/dist/js/select2.full.min.js"></script>
    <!-- iCheck 1.0.1 -->
    <script src="/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <!-- FastClick -->
    <script src="../../bower_components/fastclick/lib/fastclick.js"></script>
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
         * 新建
         */
        fnCreate = function () {
            $.ajax({
                url: "{{url('warehouse/product/part')}}",
                type: "post",
                data: $("#frmCreate").serialize(),
                success: function (response) {
                    // console.log('success:', response);
                    alert(response);
                    location.reload();
                },
                error: function (error) {
                    // console.log('fail:', error);
                    alert(error.responseText);
                    if (error.status == 401) location.href = "{{url('login')}}";
                }
            });
        };
    </script>
@endsection
