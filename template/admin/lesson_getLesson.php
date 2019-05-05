<?php
$data = \action\lesson::$data['data'];
$class = \action\lesson::$data['class'];
$list = \action\lesson::$data['list'];
$image = \action\lesson::$data['image'];
$lesson_image = \action\lesson::$data['lesson_image'];
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
            <form name="theForm" id="demo" action="./index.php?a=<?php echo $class; ?>&m=updateLesson&id=<?php echo isset($data['id']) ? $data['id'] : ""; ?>" method="post" enctype='multipart/form-data'>
                <div class="pathA ">
                    <div class="leftA">
                        <div class="leftAlist" >
                            <span>NAME 课时名</span>
                        </div>
                        <div class="leftAlist" >
                            <input class="text" name="name" type="text" value="<?php echo isset($data['name']) ? $data['name'] : ""; ?>" />
                        </div>
                        <div class="leftAlist" >
                            <span>COURSE 课程</span>
                        </div>
                        <div class="leftAlist" >
                            <select name="course_id">
                                <option value="0">请选择</option>
                                <?php if (is_array($list)) { ?>
                                    <?php foreach ($list as $k => $v) { ?>
                                        <option value="<?php echo $v['id']; ?>"  <?php echo $data['course_id'] == $v['id'] ? 'selected' : ''; ?>><?php echo $v['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="leftAlist" >
                            <span>OVERVIEW 课时简述</span>
                        </div>
                        <div class="leftAlist" >
                            <input class="text" name="overview" type="text" value="<?php echo isset($data['overview']) ? $data['overview'] : ""; ?>" />
                        </div>
                        <div class="leftAlist" >
                            <span>DETAIL 课时内容</span>
                        </div>
                        <div class="leftAlist" >
                            <script id="container" name="detail" type="text/plain">
<?php echo isset($data['detail']) ? $data['detail'] : ""; ?>
                            </script>
                        </div>
                        <div class="leftAlist" >
                            <span>ORDERBY 排序</span>
                        </div>
                        <div class="leftAlist" >
                            <input class="text" name="order_by" type="text" value="<?php echo isset($data['order_by']) ? $data['order_by'] : 50; ?>" />
                        </div>
                        <div class="leftAlist" >
                            <span>TYPE 课类型</span>
                        </div>
                        <div class="leftAlist" >
                            <select name="type">
                                <option value="audio" <?php echo $data['type'] == "audio" ? 'selected' : ''; ?>>音频</option>
                                <option value="video" <?php echo $data['type'] == "video" ? 'selected' : ''; ?>>视频</option>
                            </select>
                        </div>
                        <div class="leftAlist" >
                            <span>MEDIA 媒体资料</span>
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
                            <span>IMAGES 详细多图</span>&nbsp;<a href="javascript:void(0);" class="add_image">+</a>
                        </div>
                        <div class="leftAlist list_image" >
                            <?php if (!empty($lesson_image)) { ?>
                                <?php foreach ($lesson_image as $lk => $lv) { ?>
                                    <select name="lesson_image[]">
                                        <option value="0">请选择</option>
                                        <?php if (is_array($image)) { ?>
                                            <?php foreach ($image as $k => $v) { ?>
                                                <option value="<?php echo $v['id']; ?>"  <?php echo $lv['image_id'] == $v['id'] ? 'selected' : ''; ?>><?php echo $v['name']; ?></option>
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
            <select name="lesson_image[]">
                <option value="0">请选择</option>
                <?php if (is_array($image)) { ?>
                    <?php foreach ($image as $k => $v) { ?>
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