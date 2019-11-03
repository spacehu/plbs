<?php
$class = \action\show::$data['class'];
$data = \action\show::$data['data'];
$cat_id = !empty(\action\show::$data['cat_id']) ? \action\show::$data['cat_id'] : $data['cat_id'];
$image = \action\show::$data['image'];
$list = \action\show::$data['list'];
$typeList = \action\show::$data['typeList'];
$enterprise = \action\show::$data['enterprise'];
$enterprise_id = \action\show::$data['enterprise_id'];
$examination = \action\show::$data['examination'];
$config = \action\show::$data['config'];
if (is_array($image)) {
    foreach ($image as $k => $v) {
        if ($data['media_id'] == $v['id']) {
            $original_src = $v['original_src'];
        }
    }
}
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
            <form name="theForm" id="demo" action="./index.php?a=<?php echo $class; ?>&m=updateShow&id=<?php echo $data['id']; ?>" method="post" enctype='multipart/form-data'>
                <div class="pathA ">
                    <div class="leftA">
                        <!--                        
                        <div class="leftAlist" >
                        <span>CATEGORY 分类</span>
                        </div>
                        <div class="leftAlist" >
                        <select name="cat_id" id="cat_id">
                        <option value="0">请选择</option>
                        <?php if (is_array($list)) { ?>
                            <?php foreach ($list as $k => $v) { ?>
                                                        <option value="<?php echo $v['id']; ?>"  <?php echo $data['cat_id'] == $v['id'] ? 'selected' : ''; ?>><?php echo $v['name']; ?></option>
                            <?php } ?>
                        <?php } ?>
                        </select>
                        </div>
                        -->
                        <div class="leftAlist" >
                            <span>NAME 标题</span>
                        </div>
                        <div class="leftAlist" >
                            <div class="">
                                <input class="text" name="name" type="text" value="<?php echo isset($data['name']) ? $data['name'] : ''; ?>" />
                                <input class="text" name="cat_id" type="hidden" value="<?php echo $cat_id; ?>" />
                            </div>
                        </div>
                        <?php if (!empty($enterprise_id)) { ?>
                            <input type="hidden" name="enterprise_id" value="<?php echo $enterprise_id; ?>" />
                        <?php } else { ?>
                            <input type="hidden" name="enterprise_id" value="" />
                            <!--
                            <div class="leftAlist" >
                                <span>企业</span>
                            </div>
                            <div class="leftAlist" >
                                <select name="enterprise_id">
                                    <option value="0">请选择</option>
                                    <?php if (is_array($enterprise)) { ?>
                                        <?php foreach ($enterprise as $v) { ?>
                                            <option value="<?php echo $v['id']; ?>"  <?php echo $data['enterprise_id'] == $v['id'] ? 'selected' : ''; ?>><?php echo $v['name']; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                            -->
                        <?php } ?>
                    </div>
                    <div class="leftA c_15_16 <?php echo ($cat_id == 15 || $cat_id == 16) ? "" : "hide"; ?>">
                        <div class="leftAlist" >
                            <span>IMAGE 封面</span>
                        </div>
                        <div class="leftAlist" >
                            <div class="r_row">
                                <INPUT TYPE="file" NAME="file_url" id="f1" />
                                <input type="hidden" name="edit_doc" id="edit_doc" value="<?php echo isset($original_src) ? $original_src : './img/no_img.jpg'; ?>" />
                            </div>
                            <div class="r_row">
                                <div class="r_title">&nbsp;</div>
                                <img class="r_row_img" src="<?php echo isset($original_src) ? $original_src : './img/no_img.jpg'; ?>" />
                            </div>
                        </div>
                        <div class="leftAlist" >
                            <span>OVERVIEW 简述</span>
                        </div>
                        <div class="leftAlist" >
                            <textarea id="TextArea" name="overview"><?php echo isset($data['overview']) ? $data['overview'] : ""; ?></textarea>
                        </div>
                        <div class="leftAlist" >
                            <span>DETAIL 详细</span>
                        </div>
                        <div class="leftAlist" >
                            <script id="container" name="detail" type="text/plain">
<?php echo isset($data['detail']) ? $data['detail'] : ""; ?>
                            </script>
                        </div>
                    </div>
                    <div class="leftA c_17 <?php echo ($cat_id == 17) ? "" : "hide"; ?>">
                        <div class="leftAlist" >
                            <span>工作类型</span>
                        </div>
                        <div class="leftAlist" >
                        <!--
                            <select name="type">
                                <option value="">请选择</option>
                                <?php if (is_array($typeList)) { ?>
                                    <?php foreach ($typeList as $v) { ?>
                                        <option value="<?php echo $v; ?>"  <?php echo $data['type'] == $v ? 'selected' : ''; ?>><?php echo $v; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                            -->
                            <div class="">
                                <input class="text" name="type" type="text" value="<?php echo isset($data['type']) ? $data['type'] : ''; ?>" />
                            </div>
                        </div>
                        <div class="leftAlist" >
                            <span>薪水</span>
                        </div>
                        <div class="leftAlist" >
                            <div class="">
                                <input class="text" name="salary" type="text" value="<?php echo isset($data['salary']) ? $data['salary'] : ''; ?>" />
                            </div>
                        </div>
                        <div class="leftAlist" >
                            <span>省</span>
                        </div>
                        <div class="leftAlist" >
                            <div class="">
                                <input class="text" name="province" type="text" value="<?php echo isset($data['province']) ? $data['province'] : ''; ?>" />
                            </div>
                        </div>
                        <div class="leftAlist" >
                            <span>市</span>
                        </div>
                        <div class="leftAlist" >
                            <div class="">
                                <input class="text" name="city" type="text" value="<?php echo isset($data['city']) ? $data['city'] : ''; ?>" />
                            </div>
                        </div>
                        <div class="leftAlist" >
                            <span>区</span>
                        </div>
                        <div class="leftAlist" >
                            <div class="">
                                <input class="text" name="district" type="text" value="<?php echo isset($data['district']) ? $data['district'] : ''; ?>" />
                            </div>
                        </div>
                        <div class="leftAlist" >
                            <span>地址</span>
                        </div>
                        <div class="leftAlist" >
                            <div class="">
                                <input class="text" name="address" type="text" value="<?php echo isset($data['address']) ? $data['address'] : ''; ?>" />
                            </div>
                        </div>
                        <div class="leftAlist" >
                            <span>工龄</span>
                        </div>
                        <div class="leftAlist" >
                            <div class="">
                                <input class="text" name="age_min" type="text" value="<?php echo isset($data['age_min']) ? $data['age_min'] : ''; ?>" />
                                <input class="text" name="age_max" type="text" value="<?php echo isset($data['age_max']) ? $data['age_max'] : ''; ?>" />
                            </div>
                        </div>
                        <div class="leftAlist" >
                            <span>学历</span>
                        </div>
                        <div class="leftAlist" >
                            <div class="">
                                <input class="text" name="education" type="text" value="<?php echo isset($data['education']) ? $data['education'] : ''; ?>" />
                            </div>
                        </div>
                        <div class="leftAlist" >
                            <span>标签</span>
                        </div>
                        <div class="leftAlist" >
                            <div class="">
                                <input class="text" name="tag" type="text" value="<?php echo isset($data['tag']) ? $data['tag'] : ''; ?>" />
                            </div>
                        </div>
                        <div class="leftAlist" >
                            <span>岗位职责</span>
                        </div>
                        <div class="leftAlist" >
                            <script id="container2" name="responsibilities" type="text/plain">
<?php echo isset($data['responsibilities']) ? $data['responsibilities'] : ""; ?>
                            </script>
                        </div>
                        <div class="leftAlist" >
                            <span>任职资格</span>
                        </div>
                        <div class="leftAlist" >
                            <script id="container3" name="qualifications" type="text/plain">
<?php echo isset($data['qualifications']) ? $data['qualifications'] : ""; ?>
                            </script>
                        </div>
                        <div class="leftAlist" >
                            <span>入职资格考试</span>
                        </div>
                        <div class="leftAlist" >
                            <select name="examination_id">
                                <option value="0">请选择</option>
                                <?php if (is_array($examination)) { ?>
                                    <?php foreach ($examination as $v) { ?>
                                        <option value="<?php echo $v['id']; ?>"  <?php echo $data['examination_id'] == $v['id'] ? 'selected' : ''; ?>><?php echo $v['name']; ?></option>
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
        <script type="text/javascript">
            $(function () {
                var config = {
                    Bucket: "<?php echo $config['lib']['tencent']['cos']['bucket']; ?>",
                    Region: "<?php echo $config['lib']['tencent']['cos']['region']; ?>",
                    imagePath: "<?php echo $config['path']['image']; ?>",
                    mediaPath: "<?php echo $config['path']['media']; ?>",
                    filename: "<?php echo time(); ?>",
                    url: "<?php echo $config['lib']['tencent']['cos']['url']; ?>"
                };
                // 监听选文件
                $("#f1").on("change", function () {
                    var file = this.files[0];
                    if (!file)
                        return;
                    var fileExtension = file.name.split('.').pop();
                    var _file = config.mediaPath + "/" + config.filename + "." + fileExtension;
                    cos.putObject({
                        Bucket: config.Bucket, /* 必须 */
                        Region: config.Region, /* 必须 */
                        //Key:  file.name,              /* 必须 */
                        Key: _file,
                        Body: file
                    }, function (err, data) {
                        console.log(err || data);
                        $(".r_row_img").attr("src", config.url + _file);
                        $("#edit_doc").attr("value", config.url + _file);
                    });
                });
                // 切换
                $("#cat_id").on("change", function () {
                    if ($(this).val() === "15" || $(this).val() === "16") {
                        $(".c_15_16").show();
                        $(".c_17").hide();
                    } else if ($(this).val() === "17") {
                        $(".c_17").show();
                        $(".c_15_16").hide();
                    } else {
                        $(".c_17").hide();
                        $(".c_15_16").hide();
                    }
                });
            });
            var ue = UE.getEditor('container');
            var ue = UE.getEditor('container2');
            var ue = UE.getEditor('container3');
        </script>
    </body>
</html>