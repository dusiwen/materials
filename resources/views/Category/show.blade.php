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
        @include('Layout.alert')
        <div class="box box-primary">
            <div class="box-header with-border">
                <h1 class="box-title">{{$category->name}}&nbsp;&nbsp;型号列表</h1>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right">
{{--                    <a href="{{url('entire/model/create')}}?categoryUniqueCode={{$category->unique_code}}&page={{request()->get('page',1)}}" class="btn btn-default btn-lg btn-flat">新建</a>--}}
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover table-condensed" id="table" style="font-size: 18px;">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>名称</th>
                        <th>型号代码</th>
                        <th>设备种类</th>
                        <th>维修周期</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($entireModels as $entireModel)
                        <tr>
                            <td>{{$entireModel->id}}</td>
                            <td><a href="{{url('entire/model',$entireModel->id)}}/edit">{{$entireModel->name}}</a></td>
                            <td>{{$entireModel->unique_code}}</td>
                            <td>{{$entireModel->Category->name}}</td>
                            <td>{{$entireModel->fix_cycle_value.$entireModel->fix_cycle_unit}}</td>
                            <td>
                                <div class="btn-group btn-group-lg">
                                    <a href="{{url('entire/instance',$entireModel->unique_code)}}" class="btn btn-default btn-flat">设备列表</a>
{{--                                    <a href="{{url('entire/model',$entireModel->id)}}/edit" class="btn btn-primary btn-flat">编辑</a>--}}
{{--                                    <a href="javascript:" onclick="fnDelete({{$entireModel->id}})" class="btn btn-danger btn-flat">删除</a>--}}
                                </div>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @if($entireModels->hasPages())
                <div class="box-footer">
                    {{ $entireModels->links() }}
                </div>
            @endif
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
         * 删除
         * @param {int} id 编号
         */
        fnDelete = function (id) {
            $.ajax({
                url: `{{url('entire/model')}}/${id}`,
                type: "delete",
                data: {id: id},
                success: function (response) {
                    console.log('success:', response);
                    alert(response);
                    location.reload();
                },
                error: function (error) {
                    console.log('fail:', error);
                }
            });
        };
    </script>
@endsection
