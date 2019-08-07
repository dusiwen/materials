@extends('Layout.index')
@section('content')
    <section class="content">
        @include('Layout.alert')
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">工单历史列表</h3>
                {{--右侧最小化按钮--}}
                <div class="box-tools pull-right"></div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <ul class="timeline">
                            @foreach($fixWorkflows as $fixWorkflow)
                                <li class="time-label">
                                        <span onclick="location.href='{{url('measurement/fixWorkflow',$fixWorkflow->id)}}/edit'" class="
                                @if($fixWorkflow->flipStatus($fixWorkflow->status) == 'FIXED')
                                            bg-success
@elseif($fixWorkflow->flipStatus($fixWorkflow->status) == 'SPOT_CHECK_FAILED')
                                            bg-red
@else
                                            bg-yellow
@endif">{{$fixWorkflow->updated_at}}</span>
                                </li>
                                @if($fixWorkflow->fixWorkflowProcesses)
                                    @foreach($fixWorkflow->fixWorkflowProcesses as $fixWorkflowProcess)
                                        <li>
                                            <!-- timeline icon -->
                                            <i class="fa bg-blue fa-wrench"></i>
                                            <div class="timeline-item">
                                                <span class="time"><i class="fa fa-clock-o"></i> {{$fixWorkflowProcess->updated_at}}</span>
                                                <h3 class="timeline-header"><a href="{{url('measurement/fixWorkflow',$fixWorkflow->id)}}/edit">{{$fixWorkflowProcess->measurement->warehouseProductPart->name}}</a></h3>

                                                <div class="timeline-body">
                                                    标准值：{{$fixWorkflowProcess->measurement->allow_min != $fixWorkflowProcess->measurement->allow_max ? $fixWorkflowProcess->measurement->allow_min.$fixWorkflowProcess->measurement->unit . '～' : ''}}{{$fixWorkflowProcess->measurement->allow_max.$fixWorkflowProcess->measurement->unit}}
                                                    <br>
                                                    测试值：{{$fixWorkflowProcess->measured_value}}
                                                    <br>
                                                    测试人：{{$fixWorkflowProcess->processor->nickname}}
                                                    <br>
                                                    状态：{{$fixWorkflowProcess->status}}
                                                    <br>
                                                    说明：{{$fixWorkflowProcess->description}}
                                                </div>

                                                {{--<div class="timeline-footer">--}}
                                                {{--<a class="btn btn-primary btn-xs">...</a>--}}
                                                {{--</div>--}}
                                            </div>
                                        </li>
                                    @endforeach
                                @endif
                            @endforeach

                        </ul>
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
                url: `{{url('measurement/fixWorkflow')}}/${id}`,
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
    </script>
@endsection
