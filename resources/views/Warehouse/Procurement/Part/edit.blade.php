@extends('Layout.index')
@section('content')
    <section class="content">
        @include('Layout.alert')
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">采购单基本信息</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-md-12 control-label">订单号：<span style="font-weight: normal;">{{$warehouseProcurementPart->serial_number}}</span></label>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 control-label">下单人：<span style="font-weight: normal;">{{$warehouseProcurementPart->processor->nickname}}</span></label>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 control-label">下单时间：<span style="font-weight: normal;">{{$warehouseProcurementPart->processed_at}}</span></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">编辑零件采购单</h3>
                        {{--右侧最小化按钮--}}
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-hover table-condensed">
                            <thead>
                            <tr>
                                <th>名称</th>
                                <th>数量</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($warehouseProcurementPart->warehouseProcurementPartInstances as $warehouseProcurementPartInstance)
                                <tr>
                                    <td>{{$warehouseProcurementPartInstance->warehouseProductPart->name}}</td>
                                    <td><input type="number" class="form-control" value="{{$warehouseProcurementPartInstance->number}}" name="{{$warehouseProcurementPartInstance->id}}" onchange="fnUpdateNumber(event)"></td>
                                    <td><a href="javascript:" onclick="fnDelete({{$warehouseProcurementPartInstance->id}})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
                url: `{{url('warehouse/procurement/partInstance')}}/${id}`,
                type: "delete",
                data: {id: id},
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

        /**
         * 修改数量
         */
        fnUpdateNumber = function (event) {
            $.ajax({
                url: `{{url('warehouse/procurement/partInstance')}}/${event.target.name}`,
                type: "put",
                data: {number: event.target.value},
                async: true,
                success: function (response) {
                    // console.log('success:', response);
                    alert(response);
                    // location.reload();
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
