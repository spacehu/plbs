<?php
$data = \action\examination::$data['data'];
$class = \action\examination::$data['class'];
$test = \action\examination::$data['test'];
$examination_test = \action\examination::$data['examination_test'];
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
        <!-- 图片控件 -->
        <script src="lib/cos-js-sdk-v5-master/dist/cos-js-sdk-v5.js"></script>
        <script type="text/javascript" src="js/tencent_cos.js"></script>
        <title>无标题文档</title>
    </head>

    <body>
        <div class="status r_top">
        </div>
        <div class="content">
            <form name="theForm" id="demo" action="./index.php?a=<?php echo $class; ?>&m=updateExamination&id=<?php echo isset($data['id']) ? $data['id'] : ""; ?>" method="post" enctype='multipart/form-data'>
                <div class="pathA ">
                    <div class="leftA">
                        <div class="leftAlist" >
                            <span>试卷名</span>
                        </div>
                        <div class="leftAlist" >
                            <input class="text" name="name" type="text" value="<?php echo isset($data['name']) ? $data['name'] : ""; ?>" />
                        </div>
                        <div class="leftAlist" >
                            <span>及格线 百分比</span>
                        </div>
                        <div class="leftAlist" >
                            <input class="text" name="percentage" type="text" value="<?php echo isset($data['percentage']) ? $data['percentage'] : 0; ?>" />
                        </div>
                        <div class="leftAlist" >
                            <span>考题数</span>
                        </div>
                        <div class="leftAlist" >
                            <input class="text" name="export_count" type="text" value="<?php echo isset($data['export_count']) ? $data['export_count'] : 0; ?>" />
                        </div>
                        <div class="leftAlist" >
                            <span>卷内题目总量</span>
                        </div>
                        <div class="leftAlist" >
                            <input class="text" name="total" type="text" value="<?php echo isset($data['total']) ? $data['total'] : 0; ?>" />
                        </div>
                        <div class="leftAlist" >
                            <span>类型</span>
                        </div>
                        <div class="leftAlist" >
                            <select name="type" class="select_type">
                                <option value="random" <?php echo $data['type'] == "random" ? 'selected' : ''; ?>>随机</option>
                                <option value="regularize" <?php echo $data['type'] == "regularize" ? 'selected' : ''; ?>>固定</option>
                            </select>
                        </div>
                        <div class="leftAlist" >
                            <span>TESTS 试题列表</span>&nbsp;<a href="javascript:void(0);" class="add_image">+</a>
                        </div>
                        <div class="leftAlist list_image" >
                            <?php if (!empty($examination_test)) { ?>
                                <?php foreach ($examination_test as $lk => $lv) { ?>
                                    <div class="leftAlist" >
                                        <select name="examination_test[]" class="">
                                            <option value="0" >请选择</option>
                                            <?php if (is_array($test)) { ?>
                                                <?php foreach ($test as $k => $v) { ?>
                                                    <option value="<?php echo $v['id']; ?>" <?php echo $lv['test_id'] == $v['id'] ? 'selected' : ''; ?>><?php echo $v['name']; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                        <div class="r_title"><a href="javascript:void(0);" class="remove_image">DELETE</a></div>
                                    </div>
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
            <div class="leftAlist" >
                <select name="examination_test[]" class="">
                    <option value="0" >请选择</option>
                    <?php if (is_array($test)) { ?>
                        <?php foreach ($test as $k => $v) { ?>
                            <option value="<?php echo $v['id']; ?>" ><?php echo $v['name']; ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
                <div class="r_title"><a href="javascript:void(0);" class="remove_image">DELETE</a></div>
            </div>
        </div>
        <script type="text/javascript">
            $(function () {
                $(".add_image").click(function () {
                    $(".mod_image").children().clone().appendTo('.list_image');
                });
                $(".remove_image").live('click', function () {
                    $(this).parent().parent().remove();
                });
            });
        </script>
    </body>
</html>