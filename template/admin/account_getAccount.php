<?php
$data = \action\account::$data['data'];
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
    </div>
    <div class="content">
        <form name="theForm" id="demo" action="./index.php?a=account&m=updateAccount" method="post" enctype='multipart/form-data'>
            <div class="pathA ">
                <div class="leftA">
                    <div class="leftAlist">
                        <span>PASSWORD 修改密码</span>
                    </div>
                    <div class="leftAlist">
                        <div class="r_row">
                            <span style="width: 100px;color:#666;display:inline-block;text-align:right">新密码:</span>
                            <input class="text" name="password" type="password" value="" placeholder="请输入新密码" />
                        </div>
                        <div class="r_row">
                            <span style="width: 100px;color:#666;display:inline-block;text-align:right">确认密码:</span>
                            <input class="text" name="password_cfn" type="password" value="" placeholder="请再次确认密码" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="pathB">
                <div class="leftA">
                    <input name="" type="submit" id="submit" value="SUBMIT 提交" />
                </div>
            </div>
        </form>
    </div>
</body>

</html>