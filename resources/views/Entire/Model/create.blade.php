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
    <form class="form-horizontal" id="frmCreate">
        <section class="content">

            <div class="row">
                <div class="col-md-6">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">添加整件类型</h3>
                            {{--右侧最小化按钮--}}
                            <div class="box-tools pull-right"></div>
                        </div>
                        <br>
                        <div class="box-body" style="font-size: 18px;">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">名称：</label>
                                <div class="col-sm-10 col-md-8">
                                    <input placeholder="名称" class="form-control input-lg" type="text" required autofocus onkeydown="if(event.keyCode==13){return false;}"
                                           name="name" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">设备类型统一代码：</label>
                                <div class="col-sm-10 col-md-8">
                                    <input placeholder="设备类型统一代码" class="form-control input-lg" type="text" required onkeydown="if(event.keyCode==13){return false;}"
                                           name="unique_code" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">设备类型：</label>
                                <div class="col-sm-10 col-md-8">
                                    <select id="selCategory" name="category_unique_code" class="form-control select2" style="width: 100%;" onchange="fnGetPartModelByCategoryUniqueCode(this.value)">
                                        @foreach($categories as $categoryUniqueCode => $categoryName)
                                            @if(request()->get('categoryUniqueCode') == $categoryUniqueCode)
                                                <option value="{{$categoryUniqueCode}}" selected>{{$categoryUniqueCode .' ： '.$categoryName}}</option>
                                            @else
                                                <option value="{{$categoryUniqueCode}}">{{$categoryUniqueCode .' ： '.$categoryName}}</option>
                                            @endif

                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">维修周期单位：</label>
                                <div class="col-sm-10 col-md-8">
                                    <select name="fix_cycle_unit" class="form-control select2" style="width: 100%;">
                                        @foreach(\App\Model\EntireModel::$FIX_CYCLE_UNIT as $fixCycleUnitKey => $fixCycleUnitValue)
                                            <option value="{{$fixCycleUnitKey}}">{{$fixCycleUnitValue}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">维修周期长度：</label>
                                <div class="col-sm-10 col-md-8">
                                    <input placeholder="维修周期长度" class="form-control input-lg" type="number" min="1" max="99" step="1" required onkeydown="if(event.keyCode==13){return false;}"
                                           name="fix_cycle_value" value="1">
                                </div>
                            </div>
                            <div class="box-footer">
                                @if(request()->get('categoryUniqueCode'))
                                    <a href="{{url('category',request()->get('categoryUniqueCode'))}}?page={{request()->get('page',1)}}" class="btn btn-default btn-flat pull-left"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
                                @else
                                    <a href="{{url('entire/model')}}?page={{request()->get('page',1)}}" class="btn btn-default btn-flat pull-left"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
                                @endif

                                <a href="javascript:" onclick="fnCreate()" class="btn btn-success btn-flat pull-right"><i class="fa fa-check">&nbsp;</i>新建</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">部件类型管理</h3>
                            {{--右侧最小化按钮--}}
                            <div class="box-tools pull-right">
{{--                                <a href="javascript:" onclick="fnCreatePartModel()" class="btn-box-tool"><i class="fa fa-plus"></i></a>--}}
                                <a href="{{url('part/model/create')}}" class="btn btn-default btn-lg">新建</a>
                            </div>
                        </div>
                        <br>
                        <div class="box-body">
                            <div class="row" id="divPartModel"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>
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

            // 刷新部件列表
            fnGetPartModelByCategoryUniqueCode($('#selCategory').val());
        });

        /**
         * 打开新建部件类型窗口
         */
        fnCreatePartModel = () => {
            $.ajax({
                url: `{{url('part/modal/create')}}`,
                type: "get",
                data: {},
                async: true,
                success: function (response) {
                    console.log('success:', response);
                    // alert(response);
                    // location.reload();
                },
                error: function (error) {
                    // console.log('fail:', error);
                    if (error.status == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                },
            });
        };

        /**
         * 新建
         */
        fnCreate = function () {
            $.ajax({
                url: `{{url('entire/model')}}`,
                type: "post",
                data: $("#frmCreate").serialize(),
                success: function (response) {
                    // console.log('success:', response);
                    alert(response);
                    location.reload();
                },
                error: function (error) {
                    // console.log('fail:', error);
                    if (error.responseText == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                }
            });
        };

        /**
         * 根据设备类型获取零件类型
         * @param {string} categoryUniqueCode 设备类型统一代码
         */
        fnGetPartModelByCategoryUniqueCode = categoryUniqueCode => {
            $.ajax({
                url: `{{url('part/model')}}`,
                type: "get",
                data: {
                    type: 'category_unique_code',
                    category_unique_code: categoryUniqueCode,
                },
                async: true,
                success: function (response) {
                    html = '';
                    for (let key in response) {
                        html += `<div class="col-md-6"><label class="control-label" style="text-align: left; font-weight: normal; font-size: 18px;"><input name="part_model_unique_code[]" type="checkbox" class="minimal" value="${response[key].unique_code}" id="${response[key].unique_code}">${response[key].name}</label></div>`;
                    }
                    $('#divPartModel').html(html);
                    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                        checkboxClass: 'icheckbox_minimal-blue',
                        radioClass: 'iradio_minimal-blue'
                    });
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
