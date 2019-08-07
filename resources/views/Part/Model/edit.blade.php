@extends('Layout.index')
@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="/AdminLTE/bower_components/select2/dist/css/select2.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/AdminLTE/dist/css/AdminLTE.min.css">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="/AdminLTE/plugins/iCheck/all.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/AdminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
@endsection
@section('content')
    <section class="content">
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">保存部件类型</h3>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right"></div>
            </div>
            <br>
            <form class="form-horizontal" id="frmUpdate">
                <div class="form-group">
                    <label class="col-sm-3 control-label">名称：</label>
                    <div class="col-sm-10 col-md-8">
                        <input placeholder="名称" class="form-control" type="text" required autofocus onkeydown="if(event.keyCode==13){return false;}"
                               name="name" value="{{$partModel->name}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label text-sm">部件类型统一代码：</label>
                    <div class="col-sm-10 col-md-8">
                        <input placeholder="部件类型统一代码" class="form-control" type="text" required onkeydown="if(event.keyCode==13){return false;}"
                               name="unique_code" value="{{$partModel->unique_code}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">设备类型：</label>
                    <div class="col-sm-10 col-md-8">
                        <select name="category_unique_code" class="form-control select2" style="width: 100%;">
                            @foreach($categories as $categoryUniqueCode => $categoryName)
                                <option value="{{$categoryUniqueCode}}" {{$categoryUniqueCode == $partModel->category_unique_code}}>{{$categoryUniqueCode .' ： '.$categoryName}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="box-footer">
                    <a href="{{url('part/model')}}?page={{request()->get('page',1)}}" class="btn btn-default btn-flat pull-left btn-lg"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
                    <a href="javascript:" onclick="fnUpdate()" class="btn btn-warning btn-flat pull-right btn-lg"><i class="fa fa-check">&nbsp;</i>保存</a>
                </div>
            </form>
        </div>
    </section>
@endsection
@section('script')
    <script src="/AdminLTE/bower_components/select2/dist/js/select2.full.min.js"></script>
    <!-- iCheck 1.0.1 -->
    <script src="/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <!-- bootstrap datepicker -->
    <script src="/AdminLTE/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script>
        $(function () {
            $('.select2').select2();
            // iCheck for checkbox and radio inputs
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });
            //Date picker
            $('#datepicker').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
        });

        /**
         * 保存
         */
        fnUpdate = function () {
            $.ajax({
                url: `{{url('part/model',$partModel->id)}}`,
                type: "put",
                data: $("#frmUpdate").serialize(),
                success: function (response) {
                    console.log('success:', response);
                    alert(response);
                    location.href="{{url('part/model')}}?page{{request()->get('page',1)}}";
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
