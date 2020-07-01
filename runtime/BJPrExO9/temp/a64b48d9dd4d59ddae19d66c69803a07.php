<?php /*a:6:{s:61:"E:\project\2020\STBC_20w06\app\BJPrExO9\view\other\price.html";i:1593586913;s:54:"E:\project\2020\STBC_20w06\app\BJPrExO9\view\base.html";i:1593586454;s:64:"E:\project\2020\STBC_20w06\app\BJPrExO9\view\public\favicon.html";i:1593583464;s:63:"E:\project\2020\STBC_20w06\app\BJPrExO9\view\public\header.html";i:1593586093;s:64:"E:\project\2020\STBC_20w06\app\BJPrExO9\view\public\sidebar.html";i:1593586785;s:63:"E:\project\2020\STBC_20w06\app\BJPrExO9\view\public\footer.html";i:1593583464;}*/ ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title>aner_admin</title>
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
        
            <link rel="stylesheet" href="/static/bootstrap/bootstrap.min.css">
            <link rel="stylesheet" href="/static/admin/css/ready.min.css">
            <link rel="stylesheet" href="/static/admin/css/demo.css">

            <style>
                /*分页样式*/
                .pagination{text-align:center;margin-top:20px;margin-bottom: 20px;}
                .pagination li{margin:0px 10px; border:1px solid #e6e6e6;padding: 3px 8px;display: inline-block;}
                .pagination .active{background-color: #46A3FF;color: #fff;}
                .pagination .disabled{color:#aaa;}
            </style>
        

        <!-- For favicon png -->
<link rel="shortcut icon" type="image/icon" href="/static/logo/aner_admin_favicon.png"/>

        
<link rel="stylesheet" href="/static/dcalendar.picker/dcalendar.picker.css">

    </head>
    <body>
        <div class="wrapper">
        
            <div class="main-header">
    <div class="logo-header">
        <a href="index.html" class="logo">
            <img src="/static/logo/aner_admin_login_logo.png" width="260px" height="55px" />
        </a>
        <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse"
            data-target="collapse" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <button class="topbar-toggler more"><i class="la la-ellipsis-v"></i></button>
    </div>
    <nav class="navbar navbar-header navbar-expand-lg">
        <div class="container-fluid">
            <form class="navbar-left navbar-form nav-search mr-md-3" action="">
                <div class="input-group">
                    <input type="text" placeholder="Search ..." class="form-control" name="header_search"
                        id="header_search">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="la la-search search-icon"></i>
                        </span>
                    </div>
                </div>
            </form>
            <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
                <!-- <li class="nav-item dropdown hidden-caret">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="la la-envelope"></i>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </li> -->
                <!-- <li class="nav-item dropdown hidden-caret">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="la la-bell"></i>
                        <span class="notification">3</span>
                    </a>
                    <ul class="dropdown-menu notif-box" aria-labelledby="navbarDropdown">
                        <li>
                            <div class="dropdown-title">You have 4 new notification</div>
                        </li>
                        <li>
                            <div class="notif-center">
                                <a href="#">
                                    <div class="notif-icon notif-primary"> <i class="la la-user-plus"></i> </div>
                                    <div class="notif-content">
                                        <span class="block">
                                            New user registered
                                        </span>
                                        <span class="time">5 minutes ago</span>
                                    </div>
                                </a>
                                <a href="#">
                                    <div class="notif-icon notif-success"> <i class="la la-comment"></i> </div>
                                    <div class="notif-content">
                                        <span class="block">
                                            Rahmad commented on Admin
                                        </span>
                                        <span class="time">12 minutes ago</span>
                                    </div>
                                </a>
                                <a href="#">
                                    <div class="notif-img">
                                        <img src="/static/admin/img/profile2.jpg" alt="Img Profile">
                                    </div>
                                    <div class="notif-content">
                                        <span class="block">
                                            Reza send messages to you
                                        </span>
                                        <span class="time">12 minutes ago</span>
                                    </div>
                                </a>
                                <a href="#">
                                    <div class="notif-icon notif-danger"> <i class="la la-heart"></i> </div>
                                    <div class="notif-content">
                                        <span class="block">
                                            Farrah liked Admin
                                        </span>
                                        <span class="time">17 minutes ago</span>
                                    </div>
                                </a>
                            </div>
                        </li>
                        <li>
                            <a class="see-all" href="javascript:void(0);"> <strong>See all notifications</strong> <i
                                    class="la la-angle-right"></i> </a>
                        </li>
                    </ul>
                </li> -->
                
            </ul>
        </div>
    </nav>
</div>
        

        
            <div class="sidebar">
    <div class="scrollbar-inner sidebar-wrapper">
        <ul class="nav">
            <!-- <span class="badge badge-count">5</span> -->
            <!-- 在p标签后 -->
            <li class="nav-item active" id="actl12">
                <a href="/BJPrExO9">
                    <i class="la la-dashboard"></i>
                    <p>首页</p>
                </a>
            </li>
            <li class="nav-item" id="act16">
                <a class="" data-toggle="collapse" href="#catalog16" aria-expanded="true" onclick="catalogonoff(16)">
                    <i class="la la-cog"></i>
                    <p>系统设置</p>
                    <span class="badge"><i class="la la-caret-right" id="span16" data-status="false"></i></span>
                </a>
            </li>
            <div class="collapse in catalog16" id="catalog16" aria-expanded="true">
                <ul class="nav">
                    <li class="nav-item" id="actl19" data-topid="16">
                        <a href="/BJPrExO9/ad" style="padding-left: 40px;">
                            <i class=" la la-cc-amex"></i>
                            <p>广告管理</p>
                        </a>
                    </li>
                    <li class="nav-item" id="actl18" data-topid="16">
                        <a href="/BJPrExO9/setting" style="padding-left: 40px;">
                            <i class="la la-wrench"></i>
                            <p>网站设置</p>
                        </a>
                    </li>
                </ul>
            </div>
            <li class="nav-item" id="act30">
                <a class="" data-toggle="collapse" href="#catalog30" aria-expanded="true" onclick="catalogonoff(30)">
                    <i class="la la-users"></i>
                    <p>会员管理</p>
                    <span class="badge"><i class="la la-caret-right" id="span30" data-status="false"></i></span>
                </a>
            </li>
            <div class="collapse in catalog30" id="catalog30" aria-expanded="true">
                <ul class="nav">
                    <li class="nav-item" id="actl31" data-topid="30">
                        <a href="/BJPrExO9/user" style="padding-left: 40px;">
                            <i class="la la-street-view"></i>
                            <p>会员列表</p>
                        </a>
                    </li>
                    <li class="nav-item" id="actl37" data-topid="30">
                        <a href="/BJPrExO9/log/recharge" style="padding-left: 40px;">
                            <i class="la la-cart-arrow-down"></i>
                            <p>充值管理</p>
                        </a>
                    </li>
                    <li class="nav-item" id="actl38" data-topid="30">
                        <a href="/BJPrExO9/log/withdraw" style="padding-left: 40px;">
                            <i class="la la-cloud-upload"></i>
                            <p>提现管理</p>
                        </a>
                    </li>
                </ul>
            </div>
            <li class="nav-item" id="act39">
                <a class="" data-toggle="collapse" href="#catalog39" aria-expanded="true" onclick="catalogonoff(39)">
                    <i class="la la-connectdevelop"></i>
                    <p>应用管理</p>
                    <span class="badge"><i class="la la-caret-right" id="span39" data-status="false"></i></span>
                </a>
            </li>
            <div class="collapse in catalog39" id="catalog39" aria-expanded="true">
                <ul class="nav">
                    <li class="nav-item" id="actl40" data-topid="39">
                        <a href="/BJPrExO9/deal" style="padding-left: 40px;">
                            <i class="la la-creative-commons"></i>
                            <p>C2C管理</p>
                        </a>
                    </li>
                    <li class="nav-item" id="actl41" data-topid="39">
                        <a href="/BJPrExO9/invest" style="padding-left: 40px;">
                            <i class="la la-edit"></i>
                            <p>入金管理</p>
                        </a>
                    </li>
                    <li class="nav-item" id="actl42" data-topid="39">
                        <a href="/BJPrExO9/price" style="padding-left: 40px;">
                            <i class="la la-cc-mastercard"></i>
                            <p>STBC币价管理</p>
                        </a>
                    </li>
                    <li class="nav-item" id="actl43" data-topid="39">
                        <a href="/BJPrExO9/log/shop/apply" style="padding-left: 40px;">
                            <i class=" la la-ge"></i>
                            <p>商家申请管理</p>
                        </a>
                    </li>
                </ul>
            </div>
            <li class="nav-item" id="act26">
                <a class="" data-toggle="collapse" href="#catalog26" aria-expanded="true" onclick="catalogonoff(26)">
                    <i class="la la-server"></i>
                    <p>文章管理</p>
                    <span class="badge"><i class="la la-caret-right" id="span26" data-status="false"></i></span>
                </a>
            </li>
            <div class="collapse in catalog26" id="catalog26" aria-expanded="true">
                <ul class="nav">
                    <li class="nav-item" id="actl28" data-topid="26">
                        <a href="/BJPrExO9/cms/category" style="padding-left: 40px;">
                            <i class=" la la-puzzle-piece"></i>
                            <p>文章分类</p>
                        </a>
                    </li>
                    <li class="nav-item" id="actl29" data-topid="26">
                        <a href="/BJPrExO9/cms/article" style="padding-left: 40px;">
                            <i class="la la-tasks"></i>
                            <p>文章列表</p>
                        </a>
                    </li>
                </ul>
            </div>
            <li class="nav-item" id="act23">
                <a class="" data-toggle="collapse" href="#catalog23" aria-expanded="true" onclick="catalogonoff(23)">
                    <i class="la la-calendar"></i>
                    <p>日志管理</p>
                    <span class="badge"><i class="la la-caret-right" id="span23" data-status="false"></i></span>
                </a>
            </li>
            <div class="collapse in catalog23" id="catalog23" aria-expanded="true">
                <ul class="nav">
                    <li class="nav-item" id="actl24" data-topid="23">
                        <a href="/BJPrExO9/admin/operation/log" style="padding-left: 40px;">
                            <i class="la la-cutlery"></i>
                            <p>管理员操作日志</p>
                        </a>
                    </li>
                    <li class="nav-item" id="actl25" data-topid="23">
                        <a href="/BJPrExO9/admin/login/log" style="padding-left: 40px;">
                            <i class="la la-map-signs"></i>
                            <p>管理员登录日志</p>
                        </a>
                    </li>
                    <li class="nav-item" id="actl35" data-topid="23">
                        <a href="/BJPrExO9/user/fund/log" style="padding-left: 40px;">
                            <i class="la la-credit-card"></i>
                            <p>会员资金流水日志</p>
                        </a>
                    </li>
                    <li class="nav-item" id="actl36" data-topid="23">
                        <a href="/BJPrExO9/user/operation/log" style="padding-left: 40px;">
                            <i class="la la-newspaper-o"></i>
                            <p>会员操作日志</p>
                        </a>
                    </li>
                </ul>
            </div>
        </ul>
    </div>
</div>
        

        <div class="main-panel">
            
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">STBC价格管理</div>
                    </div>
                    <div class="card-body">
                        <div class="form-inline">
                        </div>
                        <table class="table mt-3">
                            <thead>
                                <tr>
                                    <th scope="col" width="48%">日期</th>
                                    <th scope="col" width="48%">金额</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                                <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$c): $mod = ($i % 2 );++$i;?>
                                <tr>
                                    <td><?php echo htmlentities($c['insert_time']); ?></td>
                                    <td><?php echo htmlentities($c['unit_price']); ?>CNY</td>
                                </tr>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                            </tbody>
                        </table>
                        <?php echo htmlentities($list->render()); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


            
                <footer class="footer">
    <!-- <div class="container-fluid">
        <nav class="pull-left">
            <ul class="nav">
                <li class="nav-item">
                </li>
                <li class="nav-item">
                </li>
                <li class="nav-item">
                </li>
            </ul>
        </nav>
        <div class="copyright ml-auto">
            Copyright &copy; 2018.Company name All rights reserved.<a target="_blank" href="http://sc.chinaz.com/moban/">&#x7F51;&#x9875;&#x6A21;&#x677F;</a></div>
    </div> -->
</footer>
            
        </div>
    </div>
    </body>
    
        <script src="/static/jquery/jquery.3.2.1.min.js"></script>
        <script src="/static/jquery/jquery-ui.min.js"></script>
        <script src="/static/admin/js/popper.min.js"></script>
        <script src="/static/bootstrap/bootstrap.min.js"></script>
        <script src="/static/bootstrap/bootstrap-notify.min.js"></script>
        <script src="/static/bootstrap/bootstrap-toggle.min.js"></script>
        <script src="/static/jquery/jquery.scrollbar.min.js"></script>
        <script src="/static/admin/js/ready.min.js"></script>
        <script src="/static/admin/js/demo.js"></script>
        <script src="/static/layer/layer.js"></script>

        <script>
            //头部搜索
            $("#header_search").change(function(){
                var search = $("#header_search").val();
                var url = "https://www.google.com/search?safe=active&source=hp&q="+ search +"&oq=" + search;
                window.open(url, '_blank').location;
            })

            /**
             * 在页面内弹出通知提示框
             * @method 引导通知
             * @param string type 类型（primary, info, success, warning, danger）
             * @param string title 标题
             * @param string message 内容
             * @param string placementFrom 出现位置（top, bottom）
             * @param string placementAlign 出现位置（left, right, center）
             */
            function custom_notify(type, title, message, placementFrom = 'top', placementAlign = 'center'){
                var content = {};
                content.message = message;
                content.title = title;
                content.icon = 'la la-bell';

                $.notify(content,{
                    type: type,
                    placement: {
                        from: placementFrom,
                        align: placementAlign
                    },
                    time: 1000,
                });
            }

            //目录高亮
            var current_id = "<?php echo htmlentities($current_id); ?>";
            $("#actl" + current_id).addClass('active');
            var topid = $("#actl" + current_id).data('topid')
            $("#act" + topid).addClass('active');
            $("#span" + topid).attr('class', 'la la-caret-down');
            $("#span" + topid).attr('data-status', 'true');
            $("#catalog" + topid).addClass('show');

            //目录开关
            function catalogonoff(id){
                var span_d = $("#span" + id);
                if(span_d.attr('data-status') == "false"){
                    span_d.attr('data-status', "true");
                    span_d.attr('class', 'la la-caret-down');
                }else{
                    span_d.attr('data-status', "false");
                    span_d.attr('class', 'la la-caret-right');
                }
            }

            //查看大图
            function show_image(id){
                layer.open({
                    type: 1,
                    title: false,
                    closeBtn: 0,
                    area: '516px',
                    skin: 'layui-layer-nobg', //没有背景色
                    shadeClose: true,
                    content: "<img src='"+ $("#image"+id).attr('src') +"' />"
                });
            }
        </script>
    

    
<script>

</script>

</html>