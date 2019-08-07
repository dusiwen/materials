@extends('Layout.index')
@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="/AdminLTE/bower_components/select2/dist/css/select2.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/AdminLTE/dist/css/AdminLTE.min.css">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="/AdminLTE/plugins/iCheck/all.css">
@endsection
@section('content')
    <section class="content">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">编辑机构</h3>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right"></div>
            </div>
            <br>
            <form class="form-horizontal" id="frmUpdate">
                <div class="form-group">
                    <label class="col-sm-2 control-label">名称：</label>
                    <div class="col-sm-10 col-md-8">
                        <input name="name" type="text" class="form-control" placeholder="名称" required value="{{$organization->name}}">
                    </div>
                </div>
                @if($type == 'sub')
                    <div class="form-group">
                        <label class="col-sm-2 control-label">所属机构：</label>
                        <div class="col-sm-10 col-md-8">
                            <select name="parent_id" class="form-control select2" style="width:100%;">
                                @foreach($subOrganizations as $subOrganization)
                                    @if($subOrganization->id != $organization->id)
                                        <option value="{{$subOrganization->id}}" {{$subOrganization->id == $organization->parent_id ? 'selected' : ''}}>{{$subOrganization->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                @else
                    <input type="hidden" name="parent_id" value="{{$organization->parent_id}}">
                @endif
                <div class="box-footer">
                    <a href="{{url('organization')}}" class="btn btn-default pull-left"><i class="fa fa-arrow-left">&nbsp;</i>返回</a>
                    <a href="javascript:" onclick="fnUpdate()" class="btn btn-success pull-right"><i class="fa fa-check">&nbsp;</i>新建</a>
                </div>
            </form>
        </div>
    </section>
@endsection
@section('script')
    <script src="/AdminLTE/bower_components/select2/dist/js/select2.full.min.js"></script>
    <!-- iCheck 1.0.1 -->
    <script src="/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <script type="text/javascript" src="https://webapi.amap.com/maps?v=1.4.13&key=iot-web-js"></script>
    <script>
        $('.select2').select2();
        // iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });

        /**
         * 新建
         */
        fnUpdate = function () {
            $.ajax({
                url: "{{url('organization',$organization->id)}}",
                type: "put",
                data: $("#frmUpdate").serialize(),
                success: function (response) {
                    console.log('success:', response);
                    alert(response);
                    location.reload();
                },
                error: function (error) {
                    // console.log('fail:', error);
                    alert(error.responseText);
                }
            });
        };
    </script>
@endsection
