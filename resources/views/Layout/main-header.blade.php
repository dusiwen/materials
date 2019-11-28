<header class="main-header" style="background-color: #015E63">
    <!-- Logo -->
    <a href="{{url('/')}}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
{{--        <span class="logo-mini"><b>智能</b>仓储</span>--}}
        <span class="logo-mini" style="line-height: 0;text-align: left"><img style="width: 100%;" src="/AdminLTE/dist/img/timg2.jpg"></span>
        <!-- logo for regular state and mobile devices -->
{{--        <span class="logo-lg"><span style="font-size: 18px;"><b>智能仓储</b>&nbsp;管理系统</span></span>--}}
        <span class="logo-lg" style="line-height: 0;text-align: left"><img style="width: 72%;" src="/AdminLTE/dist/img/timg1.jpg"></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <ul class="nav navbar-nav">
            <li class="dropdown tasks-menu">
                <div class="logo-lg"><div style="font-size: 15px;color: white;padding-top: 15px"><b>智能仓储</b>&nbsp;管理系统</div></div>
            </li>
        </ul>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Messages: style can be found in dropdown.less-->
                <li class="dropdown messages-menu">
{{--                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}
{{--                        <i class="fa fa-envelope-o"></i>--}}
{{--                        <span class="label label-success">4</span>--}}
{{--                    </a>--}}
                    <ul class="dropdown-menu">
                        <li class="header">You have 4 messages</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <li><!-- start message -->
                                    <a href="#">
                                        <div class="pull-left">
                                            <img src="/AdminLTE/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                                        </div>
                                        <h4>
                                            Support Team
                                            <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                        </h4>
                                        <p>Why not buy a new awesome theme?</p>
                                    </a>
                                </li>
                                <!-- end message -->
                                <li>
                                    <a href="#">
                                        <div class="pull-left">
                                            <img src="/AdminLTE/dist/img/user3-128x128.jpg" class="img-circle" alt="User Image">
                                        </div>
                                        <h4>
                                            AdminLTE Design Team
                                            <small><i class="fa fa-clock-o"></i> 2 hours</small>
                                        </h4>
                                        <p>Why not buy a new awesome theme?</p>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="pull-left">
                                            <img src="/AdminLTE/dist/img/user4-128x128.jpg" class="img-circle" alt="User Image">
                                        </div>
                                        <h4>
                                            Developers
                                            <small><i class="fa fa-clock-o"></i> Today</small>
                                        </h4>
                                        <p>Why not buy a new awesome theme?</p>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="pull-left">
                                            <img src="/AdminLTE/dist/img/user3-128x128.jpg" class="img-circle" alt="User Image">
                                        </div>
                                        <h4>
                                            Sales Department
                                            <small><i class="fa fa-clock-o"></i> Yesterday</small>
                                        </h4>
                                        <p>Why not buy a new awesome theme?</p>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="pull-left">
                                            <img src="/AdminLTE/dist/img/user4-128x128.jpg" class="img-circle" alt="User Image">
                                        </div>
                                        <h4>
                                            Reviewers
                                            <small><i class="fa fa-clock-o"></i> 2 days</small>
                                        </h4>
                                        <p>Why not buy a new awesome theme?</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="footer"><a href="#">See All Messages</a></li>
                    </ul>
                </li>
                <!-- Notifications: style can be found in dropdown.less -->
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-warning">10</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">你有10个通知</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <li>
                                    <a href="#">
                                        <i class="fa fa-users text-aqua"></i> 今天有5个新成员加入
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-warning text-yellow"></i> 很长的描述可能不适合页面并可能导致设计问题
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-users text-red"></i> 5名新成员加入
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-shopping-cart text-green"></i> 销售产品
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-user text-red"></i> 你更改了用户名
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="footer"><a href="#">查看全部</a></li>
                    </ul>
                </li>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
{{--                        @if(session()->has('account.avatar'))--}}
{{--                            <img src="/{{session()->get('account.avatar')}}" class="user-image" alt="{{session()->get('account.nickname')}}">--}}
{{--                        @endif--}}
                        @if(session()->has('account.avatar'))
                            <img src="/AdminLTE/dist/img/timg2.jpg" class="user-image" alt="{{session()->get('account.nickname')}}">
                        @endif
                        <span class="hidden-xs">{{session()->get('account.nickname')}}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
{{--                            @if(session()->has('account.avatar'))--}}
{{--                                <img src="/{{session()->get('account.avatar')}}" onclick="location.href='/profile'" class="img-circle" alt="{{session()->get('account.nickname')}}">--}}
{{--                            @endif--}}
                            @if(session()->has('account.avatar'))
                                <img src="/AdminLTE/dist/img/timg2.jpg" onclick="" class="img-circle" alt="{{session()->get('account.nickname')}}">
                            @endif
                            <p>
                                {{session()->get('account.nickname')}} - 管理员
                                <small>{{session()->get('account.created_at')}}</small>
                            </p>
                        </li>
                        <!-- Menu Body -->
{{--                    <li class="user-body">--}}
{{--                    <div class="row">--}}
{{--                    <div class="col-xs-4 text-center">--}}
{{--                    <a href="#">Followers</a>--}}
{{--                    </div>--}}
{{--                    <div class="col-xs-4 text-center">--}}
{{--                    <a href="#">Sales</a>--}}
{{--                    </div>--}}
{{--                    <div class="col-xs-4 text-center">--}}
{{--                    <a href="#">Friends</a>--}}
{{--                    </div>--}}
{{--                    </div>--}}
{{--                    </li>--}}
                    <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
{{--                                <a href="{{url('/profile')}}" class="btn btn-default btn-flat">个人中心</a>--}}
                            </div>
                            <div class="pull-right">
                                <a href="{{url('/logout')}}" class="btn btn-default btn-flat">退出登录</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
{{--                                <li>--}}
{{--                                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>--}}
{{--                                </li>--}}
            </ul>
        </div>
    </nav>
</header>
