<?php
$data = \action\enterprise::$data['data'];
$class = \action\enterprise::$data['class'];
$list = \action\enterprise::$data['list'];
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
            <form name="theForm" id="demo" action="./index.php?a=<?php echo $class; ?>&m=updateEnterprise&id=<?php echo isset($data['id']) ? $data['id'] : ""; ?>" method="post" enctype='multipart/form-data'>
                <div class="pathA ">
                    <div class="leftA">
                        <div class="leftAlist" >
                            <span>NAME 企业名</span>
                        </div>
                        <div class="leftAlist" >
                            <input class="text" name="name" type="text" value="<?php echo isset($data['name']) ? $data['name'] : ""; ?>" />
                        </div>
                        <div class="leftAlist" >
                            <span>CODE 社会识别号；组织机构代码</span>
                        </div>
                        <div class="leftAlist" >
                            <input class="text" name="code" type="text" value="<?php echo isset($data['code']) ? $data['code'] : ""; ?>" />
                        </div>
                        <div class="leftAlist" >
                            <span>USERNAME 法人</span>
                        </div>
                        <div class="leftAlist" >
                            <input class="text" name="username" type="text" value="<?php echo isset($data['username']) ? $data['username'] : ""; ?>" />
                        </div>
                        <div class="leftAlist" >
                            <span>USERCODE 法人身份证</span>
                        </div>
                        <div class="leftAlist" >
                            <input class="text" name="usercode" type="text" value="<?php echo isset($data['usercode']) ? $data['usercode'] : ""; ?>" />
                        </div>
                        <div class="leftAlist" >
                            <span>PHONE 法人电话</span>
                        </div>
                        <div class="leftAlist" >
                            <input class="text" name="phone" type="text" value="<?php echo isset($data['phone']) ? $data['phone'] : ""; ?>" />
                        </div>
                        <div class="leftAlist" >
                            <span>ADDRESS 法人地址</span>
                        </div>
                        <div class="leftAlist" >
                            <input class="text" name="address" type="text" value="<?php echo isset($data['address']) ? $data['address'] : ""; ?>" />
                        </div>
                        <div class="leftAlist" >
                            <span>USER 管理员账号</span>
                        </div>
                        <div class="leftAlist" >
                            <select name="user_id">
                                <option value="0">无图片</option>
                                <?php if (is_array($list)) { ?>
                                    <?php foreach ($list as $k => $v) { ?>
                                        <option value="<?php echo $v['id']; ?>"  <?php echo $data['user_id'] == $v['id'] ? 'selected' : ''; ?>><?php echo $v['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
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