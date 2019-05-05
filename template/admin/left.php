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
                    $(this).addClass('mainRed').next();
                    $(".second").removeClass('mainRed');
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
        <div class="title">
            <a class="first mainRed" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=material&m=staticPage'" href="javascript:void(0);" >MATERIAL 素材</a>
            <div class="sub_title">
                <div class="title">
                    <a class="second" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=category&m=index'" href="javascript:void(0);" >CATEGORY 分类</a>
                </div>
                <div class="title">
                    <a class="second" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=material&m=index&type=image'" href="javascript:void(0);" >IMAGE 图片素材</a>
                </div>
                <div class="title">
                    <a class="second" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=material&m=index&type=music'" href="javascript:void(0);" >AUDIO 音频素材</a>
                </div>
                <div class="title">
                    <a class="second" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=material&m=index&type=video'" href="javascript:void(0);" >VIDEO 视频素材</a>
                </div>
            </div>
        </div>
        <div class="title">
            <a class="first mainRed" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=show&m=staticPage'" href="javascript:void(0);" >SHOW 展示</a>
            <div class="sub_title">
                <div class="title">
                    <a class="second" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=show&m=index'" href="javascript:void(0);" >SHOW LIST 展示列表</a>
                </div>
            </div>
        </div>
        <div class="title">
            <a class="first" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=course&m=index'" href="javascript:void(0);" >COURSE 课程</a>
            <div class="sub_title">
                <div class="title">
                    <a class="second" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=lesson&m=index'" href="javascript:void(0);" >LESSON 课时</a>
                </div>
                <div class="title">
                    <a class="second" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=test&m=index'" href="javascript:void(0);" >TEST 试题</a>
                </div>
            </div>
        </div>
        <div class="title">
            <a class="first" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=statistics&m=staticPage'" href="javascript:void(0);" >STATISTICS 统计</a>
            <div class="sub_title">
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
        <?php //\mod\common::pr($_COOKIE); ?>
        <?php if (\mod\common::getSession('level') <= 1) { ?>
            <div class="title">
                <a class="first" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=system&m=staticPage'" href="javascript:void(0);" >SYSTEM  系统</a>
                <div class="sub_title">
                    <div class="title">
                        <a class="second" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=slideShow&m=index'" href="javascript:void(0);" >SLIDE SHOW 轮播显示</a>
                    </div>
                    <div class="title">
                        <a class="second" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=system&m=index'" href="javascript:void(0);" >CONFIG 配置信息</a>
                    </div>
                    <div class="title">
                        <a class="second" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=enterprise&m=index'" href="javascript:void(0);" >ENTERPRISE 企业信息</a>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if (\mod\common::getSession('level') == 0) { ?>
            <div class="title">
                <a class="first" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=user&m=index'" href="javascript:void(0);" >USER  用户</a>
            </div>
            <div class="title">
                <a class="first" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=role&m=index'" href="javascript:void(0);" >ROLE  角色</a>
            </div>
            <div class="title">
                <a class="first" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=purv&m=index'" href="javascript:void(0);" >PURV  权限</a>
            </div>
        <?php } ?>
        <div class="title">
            <a class="first" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=customer&m=index'" href="javascript:void(0);" >CUSTOMER  客户</a>
        </div>
    </body>

</html>