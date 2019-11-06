<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <!--
        <link type="text/css" rel="stylesheet" href="http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic,700italic">
        -->
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript">
            $(function () {
                $(".first").click(function () {
                    //$(".first").removeClass('mainRed').next().hide();
                    //$(this).addClass('mainRed').next().show();
                    $(".first").removeClass('mainRed').next();
                    $(".second").removeClass('mainRed');
                    if ($(this).parent().find(".sub_title").length) {
                        var siblings_second = $(this).siblings(".sub_title").find(".title").eq(0).find(".second");
                        siblings_second.addClass('mainRed').next();
                    } else {
                        $(this).addClass('mainRed').next();
                    }
                });
                $(".second").click(function () {
                    $(".first").removeClass('mainRed');
                    $(".second").removeClass('mainRed');
                    $(this).addClass('mainRed');
                });
            });
        </script>
        <title>无标题文档</title>
    </head>
    <body>
        <div id="Menu-left">
            <!-- 企业管理员模块 -->
            <div class="title">
                <a class="first" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=category&m=index&type=view'" href="javascript:void(0);" >CATEGORY 课程分类</a>
                <!--<a class="first" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=course&m=index'" href="javascript:void(0);" >COURSE 课程</a>-->
            </div>
            <div class="title">
                <a class="first" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=test&m=index'" href="javascript:void(0);" >TEST 试题</a>
            </div>
            <div class="title">
                <a class="first" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=examination&m=index'" href="javascript:void(0);" >EXAM 试卷</a>
            </div>
            <div class="title">
                <a class="first" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=customer&m=index'" href="javascript:void(0);" >CUSTOMER  学员</a>
            </div>
            <div class="title">
                <a class="first" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=department&m=index'" href="javascript:void(0);" >DEPARTMENT 部门</a>
            </div>
            <!-- 企业管理员模块 end -->
            <div class="title">
                <a class="first"  href="javascript:void(0);" >STATISTICS 统计</a>
                <div class="sub_title">
                    <div class="title">
                        <a class="second" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=statistics&m=customerList'" href="javascript:void(0);" >成员在线学习</a>
                    </div>
                    <div class="title">
                        <a class="second" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=statistics&m=userList'" href="javascript:void(0);" >员工信息维护</a>
                    </div>
                    <div class="title">
                        <a class="second" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=statistics&m=courseList'" href="javascript:void(0);" >在线课程学习</a>
                    </div>
                    <div class="title">
                        <a class="second" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=statistics&m=examinationList'" href="javascript:void(0);" >试卷统计</a>
                    </div>
                    <div class="title">
                        <a class="second" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=statistics&m=index&type=visit'" href="javascript:void(0);" >VISIT 访问统计</a>
                    </div>
                    <div class="title">
                        <a class="second" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=statistics&m=index&type=action'" href="javascript:void(0);" >ACTION 模块统计</a>
                    </div>
                    <div class="title">
                        <a class="second" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=statistics&m=index&type=page'" href="javascript:void(0);" >PAGE 单页统计</a>
                    </div>
                    <div class="title">
                        <a class="second" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=statistics&m=getStatisticsUser'" href="javascript:void(0);" >USER 用户统计</a>
                    </div>
                </div>
            </div>
            <?php if (\mod\common::getSession('level') <= 1) { ?>
                <!-- 总管理员模块 -->
                <div class="title">
                    <a class="first" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=user&m=index'" href="javascript:void(0);" >USER  管理员授权</a>
                </div>
                <div class="title">
                    <a class="first" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=role&m=index'" href="javascript:void(0);" >ROLE  管理员设置</a>
                </div>
                <div class="title">
                    <a class="first" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=show&m=staticPage'" href="javascript:void(0);" >SHOW 展示</a>
                    <div class="sub_title">
                        <div class="title">
                            <a class="second" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=show&m=index&category=15'" href="javascript:void(0);" >SHOW LIST 技术支持</a>
                        </div>
                        <div class="title">
                            <a class="second" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=show&m=index&category=16'" href="javascript:void(0);" >SHOW LIST 文库列表</a>
                        </div>
                        <div class="title">
                            <a class="second" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=show&m=index&category=17'" href="javascript:void(0);" >SHOW LIST 工作机会</a>
                        </div>
                    </div>
                </div>

                <div class="title">
                    <a class="first" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=category&m=index'" href="javascript:void(0);" >SYSTEM  系统</a>
                    <div class="sub_title">
                        <div class="title">
                            <a class="second" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=category&m=index'" href="javascript:void(0);" >MENU 菜单</a>
                        </div>
                        <div class="title">
                            <a class="second" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=slideShow&m=index'" href="javascript:void(0);" >SLIDE SHOW 轮播显示</a>
                        </div>
                        <div class="title">
                            <a class="second" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=enterprise&m=index'" href="javascript:void(0);" >ENTERPRISE 企业信息</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if (\mod\common::getSession('id') == 1) { ?>
                <div class="title">
                    <a class="first" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=purv&m=index'" href="javascript:void(0);" >PURV  权限</a>
                </div>
                <div class="title">
                    <a class="first" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=system&m=index'" href="javascript:void(0);" >CONFIG 配置信息</a>
                </div>
            <?php } ?>
            <!-- 总管理员模块 end -->

            <!-- 公共模块 -->
            <div class="title">
                <a class="first" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=account&m=getAccount'" href="javascript:void(0);" >ACCOUNT 修改密码</a>
            </div>
            <!-- 公共模块 end -->
        </div>
    </body>

</html>