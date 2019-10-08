<?php
$data = \action\examination::$data['data'];
$class = \action\examination::$data['class'];
$test = \action\examination::$data['test'];
$examination_test = \action\examination::$data['examination_test'];
$examination_test_id = \action\examination::$data['examination_test_id'];
$enterprise_id = \action\examination::$data['enterprise_id'];
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
        <!-- 复选框 -->
        <link rel="stylesheet" type="text/css" href="css/multi-select.css" />
        <script type="text/javascript" src="js/jquery.multi-select.js"></script>
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
                            <input class="text" name="enterprise_id" type="hidden" value="<?php echo $enterprise_id; ?>" />
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
                            <span>TESTS 试题列表</span>
                        </div>
                        <div class="leftAlist" >
                            <!-- 复选框 未分配的学员 -->
                            <select multiple="multiple" id="pre-selected-options" name="my-course[]">
                                <?php if (is_array($test)) { ?>
                                    <?php foreach ($test as $k => $v) { ?>
                                        <option value="<?php echo $v['id']; ?>" <?php echo is_array($examination_test_id) ? in_array($v['id'], $examination_test_id) ? 'selected' : '' : ''; ?>><?php echo $v['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                            <input class="text" name="test_add" id="test_add" type="hidden" value="" />
                            <input class="text" name="test_remove" id="test_remove" type="hidden" value="" />
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
        <script>
            var users_add = [];
            var users_remove = [];
            $('#pre-selected-options').multiSelect({
                selectableHeader: "<div class='custom-header'>题库</div>",
                selectionHeader: "<div class='custom-header'>已选题目</div>",
                afterSelect: function (values) {
                    users_add[users_add.length] = values;
                    users_remove.splice($.inArray(values, users_add), 1);
                    console.log(users_add.toString());
                    console.log(users_remove.toString());
                    $("#test_add").attr("value", users_add.toString());
                    $("#test_remove").attr("value", users_remove.toString());
                },
                afterDeselect: function (values) {
                    users_remove[users_remove.length] = values;
                    users_add.splice($.inArray(values, users_add), 1);
                    console.log(users_add.toString());
                    console.log(users_remove.toString());
                    $("#test_add").attr("value", users_add.toString());
                    $("#test_remove").attr("value", users_remove.toString());
                }
            });
        </script>
    </body>
</html>