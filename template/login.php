<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <title><?php echo mod\init::$config['shop_name']; ?>-CMS</title>
</head>

<body style="background:url(img/login_background.jpg) repeat center 0;">
<div class="wrapperlogin">
    <form action="<?php echo \mod\common::url_rewrite("index.php?a=login&m=loginPost"); ?>" method="post">
        <div class="login">
            <div class="logincon">
                <div class="logo2"><img src="./img/logo-login.png"/></div>
                <div class="logininput">
                    <p class="p8">Welcome to your</p>
                    <p class="p8">Content Management System</p>
                    <div style="padding:25px 0 5px">
                        <div class="loginlist">
                            <input name="t_username" type="text" id="text1" placeholder="USERNAME 用户名"/>
                        </div>
                        <div class="loginlist">
                            <input name="t_password" type="password" id="text1" placeholder="PASSWORD 密码"/>
                        </div>
                        <div class="loginlist">
                            <label for="select1">
                                <input name="get_c" type="checkbox" id="select1"/>&nbsp;REMEMBER ME 记住用户
                            </label>
                            <!--<a href="javascript:void(0);" class="forgotPassword" onclick="alert('coming soon...')">FORGOT PASSWORD 忘记密码</a>-->
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="loginlist">
                        <input name="" type="submit" id="button" value="ENTER 登录"/>
                    </div>
                </div>
            </div>
            <p class="p9">© <?php echo mod\init::$config['shop_name']; ?></p>
        </div>
    </form>
</div>
<div id="Copyright" class="copyright" style="bottom: 0;position: fixed;+left: calc(50vw - 120px);">
    <div style="width:100%; padding:20px 0;line-height: 1;">
        <div>
            <a href=" " class="crlink">上海技优网络科技有限公司 - 职照平台</a>
        </div>
        <div>
            <text class="crlink">上海黄浦区领展中心1期1523</text>
        </div>
        <div style="padding: 15px 0 0;">
            <a class="crlink" target="_blank" href="http://www.beian.gov.cn/">
                <img src="http://www.beian.gov.cn/img/new/gongan.png" style="float:left;"/>
                <p style="float:left;height:20px;line-height:20px;margin: 0px 0px 0px 5px; color:#939393;">沪ICP备
                    19019514号-1</p>
            </a>
        </div>
    </div>
</div>
</body>
</html>
