<?php
$data = \action\plus::$data['data'];
$list = \action\plus::$data['enterpriseList'];
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
            <?php echo $data['msg'];?>
        </div>
        <div class="content">
            <form name="theForm" id="demo" action="./index.php?a=plus&m=plusUserLessonTime" method="post" enctype='multipart/form-data'>
                <div class="pathA ">
                    <div class="leftA">
                        <div class="leftAlist" >
                            <span>ENTERPRISE 企业</span>
                        </div>
                        <div class="leftAlist" >
                            <div class="r_row">
                                <select name="enterpriseid" >
                                    <option value="0" >请选择企业</option>
                                    <?php if (!empty($list) && is_array($list)) { ?>
                                        <?php foreach ($list as $k => $v) { ?>
                                            <option value="<?php echo $v['id']; ?>" <?php echo $data['enterpriseid'] == $v['id'] ? "selected" : ""; ?> ><?php echo $v['name']; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="leftAlist" >
                            <span>TIME 奖励时间</span>
                        </div>
                        <div class="leftAlist" >
                            <div class="r_row">
                                <input class="text" name="time" type="text" value="<?php echo $data['time'];?>" /> 小时
                            </div>
                        </div>
                        <div class="leftAlist" >
                            <span><input type="checkbox" name="ignore" <?php echo ($data['ignore'] == null) ? '' : 'checked'; ?>>ONLY ONCE 只能有一次（不勾选则可以不停叠加。）</span>
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