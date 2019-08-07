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
    @include('Layout.alert')
    <form action="{{url('entire/instance')}}" method="post" enctype="multipart/form-data">
        <input type="hidden" name="type" value="BUY_IN">
        {{csrf_field()}}
        <section class="content">
            <div class="row">
                {{--整件录入--}}
                <div class="col-md-8">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h1 class="box-title">添加托盘</h1>
                            {{--右侧最小化按钮--}}
                            <div class="box-tools pull-right"></div>
                        </div>
                        <br>
                        <div class="box-body form-horizontal" style="font-size: 18px;">
{{--                            <div class="form-group form-group-lg">--}}
{{--                                <label class="col-sm-3 control-label">设备类型：</label>--}}
{{--                                @if($entireModel)--}}
{{--                                    <label class="col-sm-8 control-label" style="text-align: left; font-weight: normal;">{{$entireModel->Category->name}}</label>--}}
{{--                                    <input type="hidden" name="category_unique_code" value="{{$entireModel->category_unique_code}}">--}}
{{--                                @else--}}
{{--                                    <div class="col-sm-10 col-md-8">--}}
{{--                                        <select name="category_unique_code" class="form-control select2 input-lg" autofocus style="width: 100%;"--}}
{{--                                                onchange="fnGetEntireModelByCategoryUniqueCode(event)">--}}
{{--                                            <option value="">请选择</option>--}}
{{--                                            @foreach($categories as $categoryUniqueCode => $categoryName)--}}
{{--                                                <option value="{{$categoryUniqueCode}}">{{$categoryName}}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                            <div class="form-group form-group-lg">--}}
{{--                                <label class="col-sm-3 control-label">设备型号：</label>--}}
{{--                                @if($entireModel)--}}
{{--                                    <label class="col-sm-8 control-label" style="text-align: left; font-weight: normal;">{{$entireModel->name}}</label>--}}
{{--                                    <input type="hidden" name="entire_model_unique_code" value="{{$entireModel->unique_code}}">--}}
{{--                                @else--}}
{{--                                    <div class="col-sm-10 col-md-8">--}}
{{--                                        <select id="selEntireModel" name="entire_model_unique_code" class="form-control select2 input-lg" style="width: 100%;" onchange="fnGetPartModelByEntireModelUniqueCode(this.value)"></select>--}}
{{--                                    </div>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                            <div class="form-group">--}}
{{--                                <label class="col-sm-3 control-label">供应商名称：</label>--}}
{{--                                <div class="col-sm-10 col-md-8">--}}
{{--                                    <select name="factory_name" class="form-control select2" style="width:100%;">--}}
{{--                                        @foreach(\App\Model\Factory::all() as $factory)--}}
{{--                                            <option value="{{$factory->name}}">{{$factory->name}}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="form-group">
                                <label class="col-sm-3 control-label">托盘编号：</label>
                                <div class="col-sm-10 col-md-8">
                                    <input class="form-control input-lg" type="text" required onkeydown="if(event.keyCode==13){return false;}"
                                           name="tray_code" value="{{old('tray_code')}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">承重最小值(kg)：</label>
                                <div class="col-sm-10 col-md-8">
                                    <input class="form-control input-lg" type="text" required onkeydown="if(event.keyCode==13){return false;}"
                                           name="min" value="{{old('min')}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">承重最大值(kg)：</label>
                                <div class="col-sm-10 col-md-8">
                                    <input class="form-control input-lg" type="text" required onkeydown="if(event.keyCode==13){return false;}"
                                           name="max" value="{{old('max')}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">精度：</label>
                                <div class="col-sm-10 col-md-8">
                                    <input class="form-control input-lg" type="text" required onkeydown="if(event.keyCode==13){return false;}"
                                           name="precision" value="{{old('precision')}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">位置：</label>
                                <div class="col-sm-10 col-md-8">
                                    <input class="form-control input-lg" type="text" required onkeydown="if(event.keyCode==13){return false;}"
                                           name="place" value="{{old('place')}}">
                                </div>
                            </div>
{{--                            <div class="form-group">--}}
{{--                                <label class="col-sm-3 control-label">项目编码：</label>--}}
{{--                                <div class="col-sm-10 col-md-8">--}}
{{--                                    <input class="form-control input-lg" type="text" required onkeydown="if(event.keyCode==13){return false;}"--}}
{{--                                           name="MaterialsNumber" value="{{old('MaterialsNumber')}}">--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="form-group">--}}
{{--                                <label class="col-sm-3 control-label">物资编码：</label>--}}
{{--                                <div class="col-sm-10 col-md-8">--}}
{{--                                    <input class="form-control input-lg" type="text" required onkeydown="if(event.keyCode==13){return false;}"--}}
{{--                                           name="MaterialCode" value="{{old('MaterialCode')}}">--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="form-group">--}}
{{--                                <label class="col-sm-3 control-label">入库经办人：</label>--}}
{{--                                <div class="col-sm-10 col-md-8">--}}
{{--                                    <select name="processor_id" class="form-control select2 input-lg" style="width: 100%;">--}}
{{--                                        @foreach($accounts as $accountId => $accountNickname)--}}
{{--                                            <option value="{{$accountId}}" {{old('processor_id',null) ? $accountId == old('processor_id') ? 'selected' : '' : $accountId == session()->get('account.id') ? 'selected' : ''}}>{{$accountNickname}}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="form-group">--}}
{{--                                <label class="col-sm-3 control-label">联系人：</label>--}}
{{--                                <div class="col-sm-10 col-md-8">--}}
{{--                                    <input placeholder="联系人" class="form-control input-lg" type="text" required onkeydown="if(event.keyCode==13){return false;}"--}}
{{--                                           name="connection_name" value="{{old('connection_name','张三')}}">--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="form-group">--}}
{{--                                <label class="col-sm-3 control-label">联系电话：</label>--}}
{{--                                <div class="col-sm-10 col-md-8">--}}
{{--                                    <input placeholder="联系电话" class="form-control input-lg" type="text" required onkeydown="if(event.keyCode==13){return false;}"--}}
{{--                                           name="connection_phone" value="{{old('connection_phone','13522178057')}}">--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="form-group">--}}
{{--                                <label class="col-sm-3 control-label">入库日期：</label>--}}
{{--                                <div class="col-sm-10 col-md-8">--}}
{{--                                    <div class="input-group date">--}}
{{--                                        <
div class="input-group-addon"><i class="fa fa-calendar"></i></div>--}}
{{--                                        <input name="processed_at" type="text" class="form-control pull-right input-lg" id="datepicker" value="{{old('processed_at',date('Y-m-d'))}}">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="box-footer">
                                @if(request()->get('type'))
                                    <a href="{{url('entire/instance',request()->get(request()->get('type')))}}?page={{request()->get('page',1)}}" class="btn btn-default btn-flat pull-left btn-lg"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
                                @else
                                    <a href="{{url('entire/instance')}}?page={{request()->get('page',1)}}" class="btn btn-default btn-flat pull-left btn-lg"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
                                @endif
                                <button type="submit" class="btn btn-success btn-flat pull-right btn-lg" style="margin-left: 5px;"><i class="fa fa-check">&nbsp;</i>确定</button>
{{--                                <label class="control-label pull-right" style="text-align: left; font-weight: normal;"><input name="auto_insert_fix_workflow" type="checkbox" class="minimal input-lg" value="1">自动生成工单</label>--}}
                            </div>

                        </div>
                    </div>
                </div>
                {{--部件录入--}}
{{--                <div class="col-md-4">--}}
{{--                    <div class="box box-success">--}}
{{--                        <div class="box-header with-border">--}}
{{--                            <h1 class="box-title">添加部件</h1>--}}
{{--                        </div>--}}
{{--                        <br>--}}
{{--                        <div class="box-body" style="font-size: 18px;">--}}
{{--                            @if($entireModel)--}}
{{--                                @foreach($pivotEntireModelAndPartModels as $pivotEntireModelAndPartModel)--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label class="control-label">{{$pivotEntireModelAndPartModel->PartModel->name}}</label>--}}
{{--                                        @for($i=0; $i<$pivotEntireModelAndPartModel->number; $i++)--}}
{{--                                            <div class="form-group">--}}
{{--                                                <div class="input-group">--}}
{{--                                                    <input id="txtPartModelUniqueCode_{{$pivotEntireModelAndPartModel->PartModel->unique_code}}" type="text" class="form-control input-lg" name="{{$pivotEntireModelAndPartModel->PartModel->unique_code}}[]" value="" placeholder="厂编号">--}}
{{--                                                    <div class="input-group-addon"><a href="javascript:" onclick="fnCreateRandPartInstanceFactoryDeviceCode('{{$pivotEntireModelAndPartModel->PartModel->unique_code}}')">无厂编号</a></div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        @endfor--}}
{{--                                    </div>--}}
{{--                                @endforeach--}}
{{--                            @else--}}
{{--                                <div id="divPartForm"></div>--}}
{{--                            @endif--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
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
        });

        /**
         * 通过设备类型获取设备型号列表
         */
        fnGetEntireModelByCategoryUniqueCode = event => {
            if (event.target.value) {
                $.ajax({
                    url: `{{url('category')}}/${event.target.value}`,
                    type: "get",
                    data: {},
                    async: false,
                    success: function (response) {
                        fnFillEntireModel(response);
                        fnGetPartModelByEntireModelUniqueCode(document.getElementById('selEntireModel').value);
                    },
                    error: function (error) {
                        // console.log('fail:', error);
                        if (error.status == 401) location.href = "{{url('login')}}";
                        alert(error.responseText);
                    },
                });
            } else {
                $("#selEntireModel").html('');
            }
        };

        /**
         * 通过整件型号获取部件型号
         * @param {string} entireModelUniqueCode 整件型号唯一编号
         */
        fnGetPartModelByEntireModelUniqueCode = entireModelUniqueCode => {
            $.ajax({
                url: `{{url('part/model')}}`,
                type: "get",
                data: {type: 'entire_model_unique_code', entire_model_unique_code: entireModelUniqueCode},
                async: true,
                success: function (response) {
                    html = '';
                    for (let i = 0; i < response.length; i++) {
                        html += `
                            <div class="form-group">
                                <label class="control-label">${response[i].part_model.name}</label>`;
                        for (let j = 0; j < response[i].number; j++) {
                            html += `
                                <div class="form-group">
                                    <div class="input-group">
                                        <input id="txtPartModelUniqueCode_${response[i].part_model.unique_code}" type="text" class="form-control input-lg" name="${response[i].part_model.unique_code}[]" value="" placeholder="厂编号">
                                        <div class="input-group-addon"><a href="javascript:" onclick="fnCreateRandPartInstanceFactoryDeviceCode('${response[i].part_model.unique_code}')">无厂编号</a></div>
                                    </div>
                                </div>`;
                        }
                        html += `</div>`;
                    }
                    $("#divPartForm").html(html);
                },
                error: function (error) {
                    // console.log('fail:', error);
                    if (error.status == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                },
            });
        };

        /**
         * 填充整件型号
         * @param data
         */
        fnFillEntireModel = data => {
            html = '';
            for (let key in data) {
                html += `<option value="${data[key].unique_code}">${data[key].name}</option>`;
            }
            $("#selEntireModel").html(html);
        };

        /**
         * 生成自动的
         */
        fnCreateRandPartInstanceFactoryDeviceCode = (partModelUniqueCode) => {
            $(`#txtPartModelUniqueCode_${partModelUniqueCode}`).val(new Date().getTime());
        };
    </script>
@endsection
