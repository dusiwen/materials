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

        <div class="box box-primary" style="font-size: 18px;">
            <div class="box-header with-border">
                <h3 class="box-title">衡阳现场车间列表</h3>
            </div>
            <div class="box-body">
                <div class="col-md-3">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">设备总数统计</h3>
                        </div>
                        <div class="box-body">
                            @foreach($entireModelUniqueCodes as $entireModelUniqueCode=>$entireModelUniqueCodeCount)
                                <strong><i class="fa fa-book margin-r-5"></i> {{$entireModelUniqueCode}}</strong>
                                <p class="text-muted">{{$entireModelUniqueCodeCount}}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">车站列表</h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-hover table-condensed" style="font-size: 18px;">
                                <thead>
                                <tr>
                                    <th>车站</th>
                                    <th>设备总数</th>
                                    <th>备用</th>
                                    <th>送检</th>
                                    <th>维修</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($stationNames as $stationName)
                                    <tr>
                                        <td><a href="/report/station/{{$stationName->station_name}}">{{$stationName->station_name}}</a></td>
                                        <td>100</td>
                                        <td>50</td>
                                        <td>10</td>
                                        <td>30</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
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
         * 删除
         * @param {int} id 编号
         */
        fnDelete = id => {
            $.ajax({
                url: `{{url('report')}}/${id}`,
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
