<?php
$data = \action\course::$data['data'];
$class = \action\course::$data['class'];
$enterprise_id = \action\course::$data['enterprise_id'];
$list = \action\course::$data['list'];
$image = \action\course::$data['image'];
$enterprise = \action\course::$data['enterprise'];
$enterprise_course = \action\course::$data['enterprise_course'];
?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <script type="text/javascript" src="js/jquery.js"></script>
        <!-- 配置文件 -->
        <script type="text/javascript" src="lib/uEditor/ueditor.config.js"></script>
        <!-- 编辑器源码文件 -->
        <script type="text/javascript" src="lib/uEditor/ueditor.all.js"></script>
        <title>无标题文档</title>
    </head>

    <body>
        <div class="status r_top">
        </div>
        <div class="content">
            <form name="theForm" id="demo" action="./index.php?a=<?php echo $class; ?>&m=updateCourse&id=<?php echo isset($data['id']) ? $data['id'] : ""; ?>" method="post" enctype='multipart/form-data'>
                <div class="pathA ">
                    <div class="leftA">
                        <div class="leftAlist" >
                            <span>NAME 课程名</span>
                        </div>
                        <div class="leftAlist" >
                            <input class="text" name="name" type="text" value="<?php echo isset($data['name']) ? $data['name'] : ""; ?>" />
                        </div>
                        <div class="leftAlist" >
                            <span>CATEGORY 课程分类</span>
                        </div>
                        <div class="leftAlist" >
                            <select name="category_id">
                                <option value="0">请选择</option>
                                <?php if (is_array($list)) { ?>
                                    <?php foreach ($list as $k => $v) { ?>
                                        <option value="<?php echo $v['id']; ?>"  <?php echo $data['category_id'] == $v['id'] ? 'selected' : ''; ?>><?php echo $v['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="leftAlist" >
                            <span>OVERVIEW 课程简述</span>
                        </div>
                        <div class="leftAlist" >
                            <input class="text" name="overview" type="text" value="<?php echo isset($data['overview']) ? $data['overview'] : ""; ?>" />
                        </div>
                        <div class="leftAlist" >
                            <span>DETAIL 课程内容</span>
                        </div>
                        <div class="leftAlist" >
                            <script id="container" name="detail" type="text/plain">
<?php echo isset($data['detail']) ? $data['detail'] : ""; ?>
                            </script>
                        </div>
                        <div class="leftAlist" >
                            <span>ORDERBY 当前排序</span>
                        </div>
                        <div class="leftAlist" >
                            <input class="text" name="order_by" type="text" value="<?php echo isset($data['order_by']) ? $data['order_by'] : 50; ?>" />
                        </div>
                        <div class="leftAlist" >
                            <span>IMAGE 封面</span>
                        </div>
                        <div class="leftAlist" >
                            <select name="media_id">
                                <option value="0">无图片</option>
                                <?php if (is_array($image)) { ?>
                                    <?php foreach ($image as $k => $v) { ?>
                                        <option value="<?php echo $v['id']; ?>"  <?php echo $data['media_id'] == $v['id'] ? 'selected' : ''; ?>><?php echo $v['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="leftAlist" >
                            <span>TESTMAX 题数</span>
                        </div>
                        <div class="leftAlist" >
                            <input class="text" name="text_max" type="text" value="<?php echo isset($data['text_max']) ? $data['text_max'] : 5; ?>" />
                        </div>
                        <div class="leftAlist <?php echo!empty($enterprise_id) ? 'hide' : ''; ?>" >
                            <span>IS ENTERPRISE 隶属企业</span>&nbsp;<a href="javascript:void(0);" class="add_image">+</a>
                        </div>
                        <div class="leftAlist <?php echo!empty($enterprise_id) ? 'hide' : ''; ?> list_image" >
                            <?php if (!empty($enterprise_course)) { ?>
                                <?php foreach ($enterprise_course as $lk => $lv) { ?>
                                    <select name="enterprise_id[]">
                                        <option value="0">请选择</option>
                                        <?php if (is_array($enterprise)) { ?>
                                            <?php foreach ($enterprise as $k => $v) { ?>
                                                <option value="<?php echo $v['id']; ?>"  <?php echo $lv['enterprise_id'] == $v['id'] ? 'selected' : ''; ?>><?php echo $v['name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                <?php } ?>
                            <?php } ?>
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
        <div class="leftAlist hide mod_image">
            <select name="enterprise_id[]">
                <option value="0">请选择</option>
                <?php if (is_array($enterprise)) { ?>
                    <?php foreach ($enterprise as $k => $v) { ?>
                        <option value="<?php echo $v['id']; ?>" ><?php echo $v['name']; ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </div>
        <script type="text/javascript">
            $(function () {
                $(".add_image").click(function () {
                    $(".mod_image").children().clone().appendTo('.list_image');
                });
            });
            var ue = UE.getEditor('container');
        </script>
    </body>
</html>