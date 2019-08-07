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
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">物资列表</h3>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right"></div>
            </div>


            <div class="box-body table-responsive">
                <table class="table table-hover table-condensed" id="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>物资编码</th>
                        <th>物资名称</th>
                        <th>单位</th>
                        <th>数量</th>
                        <th>总重量(kg)</th>
                        <th>单价(元)</th>
                        <th>金额(元)</th>
                        <th>备注</th>
                        <th>扫码数量</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($maiterials as $v)
                        <tr>
                            <th>{{$i++}}</th>
                            <th>{{$v->StockIn_MaterialCode}}</th>
                            <th>{{$v->StockIn_MaterialName}}</th>
                            <th>{{$v->StockIn_Unit}}</th>
                            <th>{{$v->StockIn_Number}}</th>
                            <th>{{$v->StockIn_Weight}}</th>
                            <th>{{$v->StockIn_Price}}</th>
                            <th>{{$v->StockIn_Sum}}</th>
                            <th>{{$v->StockIn_Remark}}</th>
                            <th>0</th>
{{--                            <td>--}}

{{--                                <div class="btn-group btn-group-lg">--}}
{{--                                    <a href="javascript:" onclick="fnDelete({{$v->id}})" class="btn btn-danger btn-flat">删除</a>--}}
{{--                                </div>--}}
{{--                            </td>--}}
                            {{--                                    <td>--}}

                            {{--                                        <div class="btn-group btn-group-lg">--}}
                            {{--                                            <a href="{{url('warehouse/report',$warehouseReport->serial_number)}}?page={{request()->get('page',1)}}&direction={{request()->get('direction')}}&updated_at={{request()->get('updated_at')}}&category_unique_code={{request()->get('category_unique_code')}}&type={{request()->get('type')}}" class="btn btn-primary btn-flat">查看</a>--}}
                            {{--                                            <a href="javascript:" onclick="fnDelete({{$warehouseReport->serial_number}})" class="btn btn-danger btn-flat">删除</a>--}}
                            {{--                                        </div>--}}
                            {{--                                    </td>--}}
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>


            <div class="box-footer">
                {{--                        <a href="{{url('warehouse/report')}}?page={{request()->get('page',1)}}" class="btn btn-default btn-flat pull-left"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>--}}
                <a href="javascript:" onclick="fn({{$time}})" class="btn btn-warning btn-flat pull-right"><i class="fa fa-check">&nbsp;</i>确定</a>
            </div>
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
         * 扫码确定,数量无误后跳转
         */
        fn = function (id) {
            $.ajax({
                url: `{{url('warehouse/plan')}}/${id}`,
                type: "put",
                data: {time: id},
                success: function (response) {
                    // console.log('success:', response);
                    // alert(response);
                    location.href = "{{url('warehouse/report')}}";
                },
                error: function (error) {
                    console.log('fail:', error);
                    alert(error.responseText);
                    if (error.status == 401) location.href = "{{url('login')}}";
                }
            });
        };
    </script>
@endsection
