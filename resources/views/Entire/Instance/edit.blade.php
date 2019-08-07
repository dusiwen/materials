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
                <h1 class="box-title">编辑设备</h1>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right"></div>
            </div>
            <br>
            <form class="form-horizontal" id="frmUpdate" style="font-size: 18px;">
                <div class="form-group form-group-lg">
                    <label class="col-md-3 control-label">型号：</label>
                    <label class="col-md-3 control-label" style="text-align: left; font-weight: normal;">{{$entireInstance->EntireModel->name}}（{{$entireInstance->EntireModel->unique_code}}）</label>
                    <label class="col-md-3 control-label">类型：</label>
                    <label class="col-md-3 control-label" style="text-align: left; font-weight: normal;">{{$entireInstance->Category->name}}（{{$entireInstance->Category->unique_code}}）</label>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">所编号：</label>
                    <div class="col-sm-10 col-md-8">
                        <div class="input-group">
                            <input class="form-control input-lg"
                                   name="serial_number" type="text" placeholder="所编号" value="{{$entireInstance->serial_number}}"
                                   required autofocus onkeydown="if(event.keyCode==13){return false;}">
                            <div class="input-group-btn">
                                <a href="javascript:" class="btn btn-info btn-flat btn-lg" onclick="fnCreateInstall('{{$entireInstance->identity_code}}')">重新安装</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">供应商：</label>
                    <div class="col-sm-10 col-md-8">
                        <div class="input-group">
                            <select name="factory_name" class="form-control select2" style="width:100%;">
                                @foreach(\App\Model\Factory::all() as $factory)
                                    <option value="{{$factory->name}}" {{$factory->name == $entireInstance->factory_name ? 'selected' : ''}}>{{$factory->name}}</option>
                                @endforeach
                            </select>
                            <div class="input-group-addon">
                                <span style="font-size: 18px;">出厂编号：{{$entireInstance->factory_device_code}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">主/备用状态：</label>
                    <label class="control-label" style="text-align: left; font-weight: normal;"><input name="is_main" type="radio" {{$entireInstance->is_main == 1 ? 'checked' : ''}} class="minimal" value="1">主用</label>
                    <label class="control-label" style="text-align: left; font-weight: normal;"><input name="is_main" type="radio" {{$entireInstance->is_main == 0 ? 'checked' : ''}} class="minimal" value="0">备用</label>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">安装位置：</label>
                    <label class="col-sm-8 control-label" style="font-weight: normal; text-align: left;">{{$entireInstance->maintain_station_name}}&nbsp;&nbsp;{{$entireInstance->maintain_location_code}}</label>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">安装日期：</label>
                    <label class="col-sm-8 control-label" style="font-weight: normal; text-align: left;">{{$entireInstance->last_installed_time ? date('Y-m-d',$entireInstance->last_installed_time) : ''}}</label>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">在库状态：</label>
                    <label class="col-sm-8 control-label" style="font-weight: normal; text-align: left;">{{$entireInstance->in_warehouse == 1 ? '在库' : '库外'}}</label>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">状态：</label>
                    <label class="col-md-8 control-label" style="text-align: left; font-weight: normal;">{{$entireInstance->status}}</label>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">下次周期修时间：</label>
                    <label class="col-sm-8 control-label" style="font-weight: normal; text-align: left;">{{$entireInstance->next_fixing_day}}</label>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">周期修：</label>
                    <div class="col-sm-8 col-md-8">
                        <select name="fix_cycle_unit" class="form-control select2" style="width:100%;">
                            @foreach(\App\Model\EntireInstance::$FIX_CYCLE_UNIT as $fixCycleUnitKey => $fixCycleUnitValue)
                                <option value="{{$fixCycleUnitKey}}" {{$entireInstance->fix_cycle_unit == $fixCycleUnitKey ? 'selected' : ''}}>{{$fixCycleUnitValue}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">周期长度：</label>
                    <div class="col-sm-8 col-md-8">
                        <input type="text" name="fix_cycle_value" class="form-control" value="{{$entireInstance->fix_cycle_value}}">
                        <div class="help-block" style="color: red;">0代表使用该型号的周期</div>
                    </div>
                </div>

                <div class="box-footer">
                    {{--<a href="{{url('entire/instance')}}" class="btn btn-default btn-flat pull-left btn-lg"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>--}}
                    <a href="javascript:" onclick="history.back(-1);" class="btn btn-default btn-flat pull-left btn-lg"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
                    <a href="javascript:" onclick="fnUpdate()" class="btn btn-warning btn-flat pull-right btn-lg"><i class="fa fa-check">&nbsp;</i>保存</a>
                </div>
            </form>
        </div>
        <div id="divModalInstall"></div>
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
                url: `{{url('entire/instance',$entireInstance->identity_code)}}`,
                type: "put",
                data: $("#frmUpdate").serialize(),
                success: function (response) {
                    console.log('success:', response);
                    alert(response);
                    location.href = "{{url('entire/instance',$entireInstance->entire_model_unique_code)}}?page{{request()->get('page',1)}}";
                },
                error: function (error) {
                    // console.log('fail:', error);
                    if (error.responseText == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                }
            });
        };

        /**
         * 打开出库窗口
         * @param entireInstanceIdentityCode
         */
        fnCreateInstall = (entireInstanceIdentityCode) => {
            $.ajax({
                url: `{{url('entire/instance/install')}}`,
                type: "get",
                data: {entireInstanceIdentityCode: entireInstanceIdentityCode},
                async: true,
                success: function (response) {
                    $("#divModalInstall").html(response);
                    $("#modalInstall").modal("show");
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
