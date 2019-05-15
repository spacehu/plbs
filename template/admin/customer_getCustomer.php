<?php
$data = \action\customer::$data['data'];
$userCourse = \action\customer::$data['userCourse'];
$course = \action\customer::$data['course'];
?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <script type="text/javascript" src="js/jquery.js"></script>
        <title>无标题文档</title>
    </head>

    <body>
        <div class="status r_top">
            <p>用户信息</p>
        </div>
        <div class="content">
            <div class="pathA ">
                <div class="leftA">
                    <div class="leftAlist" >
                        <span>NAME 用户名</span>
                    </div>
                    <div class="leftAlist" >
                        <div class="r_row">
                            <?php echo isset($data['name']) ? $data['name'] : ''; ?>
                        </div>
                    </div>
                    <div class="leftAlist" >
                        <span>PHONE 手机</span>
                    </div>
                    <div class="leftAlist" >
                        <div class="r_row">
                            <?php echo isset($data['phone']) ? $data['phone'] : ''; ?>
                        </div>
                    </div>
                    <div class="leftAlist" >
                        <span>NICKNAME 昵称</span>
                    </div>
                    <div class="leftAlist" >
                        <div class="r_row">
                            <?php echo isset($data['nickname']) ? $data['nickname'] : ''; ?>
                        </div>
                    </div>
                    <div class="leftAlist" >
                        <span>PHOTO 头像</span>
                    </div>
                    <div class="leftAlist" >
                        <div class="r_row">
                            <img src="<?php echo isset($data['photo']) ? $data['photo'] : ''; ?>" />
                        </div>
                    </div>
                    <div class="leftAlist" >
                        <span>BRITHDAT 生日</span>
                    </div>
                    <div class="leftAlist" >
                        <div class="r_row">
                            <?php echo isset($data['brithday']) ? $data['brithday'] : ''; ?>
                        </div>
                    </div>
                    <div class="leftAlist" >
                        <span>CITY 城市</span>
                    </div>
                    <div class="leftAlist" >
                        <div class="r_row">
                            <?php echo isset($data['city']) ? $data['city'] : ''; ?>
                        </div>
                    </div>
                    <div class="leftAlist" >
                        <span>PROVINCE 省</span>
                    </div>
                    <div class="leftAlist" >
                        <div class="r_row">
                            <?php echo isset($data['province']) ? $data['province'] : ''; ?>
                        </div>
                    </div>
                    <div class="leftAlist" >
                        <span>DISTRICT 区域</span>
                    </div>
                    <div class="leftAlist" >
                        <div class="r_row">
                            <?php echo isset($data['district']) ? $data['district'] : ''; ?>
                        </div>
                    </div>
                    <div class="leftAlist" >
                        <span>EMAIL 电子邮件</span>
                    </div>
                    <div class="leftAlist" >
                        <div class="r_row">
                            <?php echo isset($data['email']) ? $data['email'] : ''; ?>
                        </div>
                    </div>
                    <div class="leftAlist" >
                        <span>SEX 性别</span>
                    </div>
                    <div class="leftAlist" >
                        <div class="r_row">
                            <?php echo isset($data['sex']) ? $data['sex'] : ''; ?>
                        </div>
                    </div>
                    <div class="leftAlist" >
                        <span>REGISTER TIME 注册时间</span>
                    </div>
                    <div class="leftAlist" >
                        <div class="r_row">
                            <?php echo isset($data['add_time']) ? $data['add_time'] : "****-**-** **:**:**"; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>