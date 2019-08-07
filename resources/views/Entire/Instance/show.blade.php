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
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h1 class="box-title">筛选</h1>
                        {{--右侧最小化按钮--}}
                        <div class="box-tools pull-right"></div>
                    </div>
                    <div class="box-body form-horizontal">
                        <form action="">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <div class="input-group-addon">状态</div>
                                        <select name="status" class="form-control select2" style="width:100%;">
                                            <option value="">全部</option>
                                            <option value="INSTALLING" {{request()->get('status') == 'INSTALLING' ? 'selected' : ''}}>安装中</option>
                                            <option value="INSTALLED" {{request()->get('status') == 'INSTALLED' ? 'selected' : ''}}>已安装</option>
                                            <option value="FIXING" {{request()->get('status') == 'FIXING' ? 'selected' : ''}}>检修中</option>
                                            <option value="FIXED" {{request()->get('status') == 'FIXED' ? 'selected' : ''}}>可用</option>
                                            <option value="RETURN_FACTORY" {{request()->get('status') == 'RETURN_FACTORY' ? 'selected' : ''}}>返厂维修</option>
                                            <option value="FACTORY_RETURN" {{request()->get('status') == 'FACTORY_RETURN' ? 'selected' : ''}}>返厂入所</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <div class="input-group-addon">时间类型</div>
                                        <select name="date_type" class="form-control select2" style="width:100%;">
                                            <option value="">全部</option>
                                            <option value="create" {{request()->get('date_type') == 'create' ? 'selected' : ''}}>入所时间</option>
                                            <option value="update" {{request()->get('date_type') == 'update' ? 'selected' : ''}}>最后修改时间</option>
                                            <option value="install" {{request()->get('date_type') == 'install' ? 'selected' : ''}}>最后安装时间</option>
                                            <option value="fix" {{request()->get('date_type') == 'fix' ? 'selected' : ''}}>最后检修时间</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <div class="input-group-addon">时间段</div>
                                        <input name="date" type="text" class="form-control pull-right" id="date" value="{{request()->get('date',date("Y-m-d").'~'.date("Y-m-d"))}}">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <button class="btn btn-info btn-flat">筛选</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="box-title">设备列表</h1>
                        {{--右侧最小化按钮--}}
                        <div class="box-tools pull-right">
                            <a href="{{url('warehouse/report/scanInBatch')}}?page={{request()->get('page',1)}}" class="btn btn-default btn-lg btn-flat">批量扫码入所</a>
                            <a href="{{url('entire/instance/create')}}?page={{request()->get('page',1)}}&type=entire_model_unique_code&entire_model_unique_code={{$entireModel->unique_code}}" class="btn btn-default btn-lg btn-flat">新设备</a>
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-hover table-condensed table-striped" id="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>型号</th>
                                <th>类型</th>
                                <th>流水号</th>
                                <th>供应商</th>
                                <th>出厂代码</th>
                                <th>安装位置</th>
                                <th>安装时间</th>
                                <th>主/备用</th>
                                <th>状态</th>
                                <th>在库状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($entireInstances as $entireInstance)
                                <tr>
                                    <td>{{$entireInstance->id}}</td>
                                    <td><a href="{{url('search',$entireInstance->identity_code)}}">{{$entireInstance->EntireModel ? $entireInstance->EntireModel->name.'（'.$entireInstance->entire_model_unique_code.'）' : ''}}</a></td>
                                    <td>{{$entireInstance->Category ? $entireInstance->Category->name : ''}}</td>
                                    <td>{{$entireInstance->serial_number}}</td>
                                    <td>{{$entireInstance->factory_name}}</td>
                                    <td>{{$entireInstance->factory_device_code}}</td>
                                    <td>{{$entireInstance->maintain_station_name.'&nbsp;'.$entireInstance->maintain_location_code}}</td>
                                    <td>{{$entireInstance->last_installed_time ? date('Y-m-d',$entireInstance->last_installed_time) : ''}}</td>
                                    <td>{{$entireInstance->is_main ? '主用' : '备用'}}</td>
                                    <td>{{$entireInstance->status}}</td>
                                    <td>{{$entireInstance->in_warehouse ? '在库' : '库外'}}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{url('entire/instance',$entireInstance->identity_code)}}/edit?page={{request()->get('page',1)}}" class="btn btn-primary btn-flat">修改</a>
                                            @if($entireInstance->FixWorkflow)
                                                @if($entireInstance->FixWorkflow->status != 'FIXED')
                                                    {{--查看检修单--}}
                                                    <a href="{{url('measurement/fixWorkflow',$entireInstance->fix_workflow_serial_number)}}/edit?page={{request()->get('page',1)}}" class="btn btn-warning btn-flat">检修</a>
                                                @endif
                                            @else
                                                {{--新建检修单--}}
                                                <a href="{{url('measurement/fixWorkflow/create')}}?page={{request()->get('page',1)}}&type=FIX&identity_code={{$entireInstance->identity_code}}" class="btn btn-warning btn-flat">新检修</a>
                                            @endif
                                            @if(array_flip(\App\Model\EntireInstance::$STATUS)[$entireInstance->status] == 'INSTALLED' || array_flip(\App\Model\EntireInstance::$STATUS)[$entireInstance->status] == 'INSTALLING')
                                                <a href="javascript:" onclick="fnFixingIn('{{$entireInstance->identity_code}}')" class="btn btn-default btn-flat">入所</a>
                                            @else
                                                <a href="javascript:" class="btn btn-default btn-flat disabled" disabled="disabled">入所</a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($entireInstances->hasPages())
                        <div class="box-footer">
                            {{ $entireInstances->appends([
                            'categoryUniqueCode'=>request()->get('categoryUniqueCode'),
                            'entireModelUniqueCode'=>request()->get('entireModelUniqueCode'),
                            'updatedAt'=>request()->get('updatedAt'),
                            'status'=>request()->get('status'),
                            'date_type'=>request()->get('date_type'),
                            'date'=>request()->get('date'),
                            ])->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="divModalEntireInstanceFixing"></div>
        <div id="divModalFixWorkflowInOnce"></div>
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
            $('#date').daterangepicker({

                locale: {
                    format: "YYYY-MM-DD",
                    separator: "~",
                    daysOfWeek: ["日", "一", "二", "三", "四", "五", "六"],
                    monthNames: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"]
                }
            });
        });

        /**
         * 报废
         * @param {string} identityCode 编号
         */
        fnDelete = function (identityCode) {
            $.ajax({
                url: `{{url('entire/instance/scrap')}}/${identityCode}`,
                type: "post",
                data: {},
                async: true,
                success: function (response) {
                    // console.log('success:', response);
                    alert(response);
                    location.reload();
                },
                error: function (error) {
                    // console.log('fail:', error);
                    if (error.status == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                },
            });
        };

        /**
         * 打开入所窗口
         * @param entireInstanceIdentityCode
         */
        fnFixingIn = (entireInstanceIdentityCode) => {
            $.ajax({
                url: `{{url('entire/instance/fixingIn')}}/${entireInstanceIdentityCode}`,
                type: "get",
                data: {},
                async: true,
                success: function (response) {
                    console.log('success:', response);
                    // alert(response);
                    // location.reload();
                    $("#divModalFixWorkflowInOnce").html(response);
                    $("#modalFixWorkflowInOnce").modal("show");
                },
                error: function (error) {
                    // console.log('fail:', error);
                    if (error.status == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                },
            });
        };
    </script>
@endsection
