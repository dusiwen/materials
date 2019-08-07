@extends('Layout.index')
@section('content')
    <section class="content">
        @include('Layout.alert')
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">零件采购单列表</h3>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right">
                    <form id="frmUpload" style="display: none;">
                        <input id="fileUpload" style="display: none; width: 1px;" type="file" name="file" onchange="fileChange('${base}')"/>　　<!-- 定义change事件,选择文件后触发 -->
                    </form>
                    <a href="javascript:" class="btn btn-box-tool" onclick="$('[name=file]').click()"><i class="fa fa-upload"></i></a>
                    <a href="{{url('downloadProcurementPartTemplateExcel')}}" target="_blank" class="btn btn-box-tool"><i class="fa fa-download"></i></a>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover table-condensed" id="table">
                    <thead>
                    <tr>
                        <th>序列号</th>
                        <th>下单人</th>
                        <th>下单时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($warehouseProcurementParts as $warehouseProcurementPart)
                        <tr>
                            <td>{{$warehouseProcurementPart->serial_number}}</td>
                            <td>{{$warehouseProcurementPart->processor->nickname}}</td>
                            <td>{{$warehouseProcurementPart->processed_at}}</td>
                            <td>
                                <a href="javascript:" class="btn btn-default btn-sm" onclick="fnCreateWarehouseReportProductPart({{$warehouseProcurementPart->id}})"><i class="fa fa-sign-in">&nbsp;</i>办理入库</a>
                                <a href="{{url('warehouse/procurement/part',$warehouseProcurementPart->id)}}" class="btn btn-default btn-sm"><i class="fa fa-bars">&nbsp;</i>入库记录</a>
                                <a href="{{url('warehouse/procurement/part',$warehouseProcurementPart->id)}}/edit" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
                                <a href="javascript:" onclick="fnDelete({{$warehouseProcurementPart->id}})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @if($warehouseProcurementParts->hasPages())
                <div class="box-footer">
                    {{ $warehouseProcurementParts->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
@section('script')
    <script>
        /**
         * 删除
         * @param {int} id 编号
         */
        fnDelete = function (id) {
            $.ajax({
                url: `{{url('warehouse/procurement/part')}}/${id}`,
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

        /**
         * 上传文件
         */
        fileChange = () => {
            var fileName = $('#fileUpload').val();　　　　　　　　　　　　　　　　　　//获得文件名称
            var fileType = fileName.substr(fileName.length - 4, fileName.length);　　//截取文件类型,如(.xls)
            $.ajax({
                url: "{{url('warehouse/procurement/part')}}",　　　　　　　　　　//上传地址
                type: 'POST',
                cache: false,
                data: new FormData($('#frmUpload')[0]),　　　　　　　　　　　　　//表单数据
                processData: false,
                contentType: false,
                success: response => {
                    // console.log(response);
                    // alert(response);
                    location.reload();
                }, error: error => {
                    if (error.status == 401) location.href = "{{url('login')}}";
                    alert(error.responseText);
                }
            });
        };

        /**
         * 打开零件入库窗口
         * @param {int} warehouseProcurementPartId 零件采购单编号
         */
        fnCreateWarehouseReportProductPart = (warehouseProcurementPartId) => {
            $.ajax({
                url: `{{url('warehouse/report/productPart/create')}}?warehouseProcurementPartId=${warehouseProcurementPartId}`,
                type: "get",
                data: {},
                async: true,
                success: function (response) {
                    // console.log('success:', response);
                    $("#divModal").html(response);
                    $("#modal").modal("show");
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
