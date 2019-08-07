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
                <h3 class="box-title">台账列表</h3>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right">
                    <a href="{{url('maintain/create')}}?page={{request()->get('page',1)}}" class="btn btn-default btn-lg btn-flat">新建</a>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover table-condensed" id="table" style="font-size: 18px;">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>名称</th>
                        <th>父级</th>
                        <th>统一代码</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($maintains as $maintain)
                        <tr>
                            <td>{{$maintain->id}}</td>
                            <td>{{$maintain->name}}</td>
                            <td>{{$maintain->Parent ? $maintain->Parent->name : ''}}</td>
                            <td>{{$maintain->unique_code}}</td>
                            <td>
                                <div class="btn-group btn-group-lg">
                                    <a href="{{url('maintain',$maintain->unique_code)}}/edit?page={{request()->get('page',1)}}" class="btn btn-primary btn-flat">编辑</a>
                                    <a href="javascript:" onclick="fnDelete('{{$maintain->unique_code}}')" class="btn btn-danger btn-flat">删除</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @if($maintains->hasPages())
                <div class="box-footer">
                    {{ $maintains->links() }}
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
         * @param {string} uniqueCode 统一标识代码
         */
        fnDelete = uniqueCode => {
            $.ajax({
                url: `{{url('maintain')}}/${uniqueCode}`,
                type: "delete",
                data: {},
                success: function (response) {
                    // console.log('success:', response);
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
