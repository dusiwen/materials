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
                <h3 class="box-title">物资列表</h3>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right">
                    <a href="{{url('part/model/create')}}?page={{request()->get('page',1)}}" class="btn btn-default btn-flat btn-lg">新建</a>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover table-condensed" id="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>物资编码</th>
                        <th>物资名称</th>
                        <th>单位</th>
                        <th>重量(kg)</th>
                        <th>备注</th>
                        <th>添加时间</th>
                        <th>使用年限</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($materials as $v)
                        <tr>
                            <td>{{$i--}}</td>
                            <td>{{$v->MaterialCode}}</td>
                            <td>{{$v->MaterialName}}</td>
                            <td>{{$v->unit}}</td>
                            <td>{{$v->EachWeight}}</td>
                            <td>{{$v->remark}}</td>
                            <td>{{date("Y-m-d H:i:s",$v->AddTime)}}</td>
                            <td>{{$v->ServiceLife}}</td>
                            <td>
                                <div class="btn-group btn-group-lg">
{{--                                    <a href="{{url('part/model',$v->id)}}/edit?page={{request()->get('page',1)}}" class="btn btn-primary btn-flat">编辑</a>--}}
                                    <a href="javascript:" onclick="fnDelete({{$v->id}})" class="btn btn-danger btn-flat">删除</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
{{--            分页--}}
{{--            @if($partModels->hasPages())--}}
{{--                <div class="box-footer">--}}
{{--                    {{ $partModels->links() }}--}}
{{--                </div>--}}
{{--            @endif--}}
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
                url: `{{url('part/model')}}/${id}`,
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
