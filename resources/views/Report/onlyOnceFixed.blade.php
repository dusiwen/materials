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
                <div class="row">
                    <div class="col-md-8">
                        <h1 class="box-title pull-left">单次检修合格列表</h1>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <select name="parent_id" class="form-control select2" style="width:100%;" onchange="location.href=`?type=${this.value}`">
                                <option value="0" {{request()->get("type") == 0 ? "selected" :""}}>当月</option>
                                <option value="1" {{request()->get("type") == 1 ? "selected" :""}}>上月</option>
                                <option value="3" {{request()->get("type") == 3 ? "selected" :""}}>近三个月</option>
                                <option value="6" {{request()->get("type") == 6 ? "selected" :""}}>近六个月</option>
                                <option value="12" {{request()->get("type") == 12 ? "selected" :""}}>近十二个月</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover table-condensed" style="font-size: 18px;">
                    <thead>
                    <tr>
                        <th>检修单</th>
                        <th>类型</th>
                        <th>整件</th>
                        <th>创建时间</th>
                        <th>完成时间</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($fixWorkflows as $fixWorkflow)
                        <tr>
                            <td><a href="{{url('measurement/fixWorkflow',$fixWorkflow->serial_number)}}/edit">{{$fixWorkflow->serial_number}}</a></td>
                            <td>{{$fixWorkflow->EntireInstance->Category->name}}（{{$fixWorkflow->EntireInstance->category_unique_code}}）</td>
                            <td><a href="{{url('search',$fixWorkflow->entire_instance_identity_code)}}">{{$fixWorkflow->EntireInstance->serail_nubmer ?: $fixWorkflow->EntireInstance->factory_device_code}}</a>（{{$fixWorkflow->EntireInstance->EntireModel->name}}{{$fixWorkflow->EntireInstance->entire_model_unique_code}}）</td>
                            <td>{{$fixWorkflow->created_at}}</td>
                            <td>{{$fixWorkflow->updated_at}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @if($fixWorkflows->hasPages())
                <div class="box-footer">
                    {{ $fixWorkflows->links() }}
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
        fnDelete = id => {
            $.ajax({
                url: `{{url('report/onlyOnceFixed')}}/${id}`,
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
