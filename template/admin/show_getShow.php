<?php
$data = \action\show::$data['data'];
$image = \action\show::$data['image'];
$list = \action\show::$data['list'];
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
            <form name="theForm" id="demo" action="./index.php?a=show&m=updateShow&id=<?php echo $data['id']; ?>" method="post" enctype='multipart/form-data'>
                <div class="pathA ">
                    <div class="leftA">
                        <div class="leftAlist" >
                            <span>NAME 标题</span>
                        </div>
                        <div class="leftAlist" >
                            <div class="">
                                <input class="text" name="name" type="text" value="<?php echo isset($data['name']) ? $data['name'] : ''; ?>" />
                            </div>
                        </div>
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
                        <div class="leftAlist" >
                            <span>CATEGORY 分类</span>
                        </div>
                        <div class="leftAlist" >
                            <select name="cat_id">
                                <option value="0">请选择</option>
                                <?php if (is_array($list)) { ?>
                                    <?php foreach ($list as $k => $v) { ?>
                                        <option value="<?php echo $v['id']; ?>"  <?php echo $data['cat_id'] == $v['id'] ? 'selected' : ''; ?>><?php echo $v['name']; ?></option>
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
            });
            var ue = UE.getEditor('container');
        </script>
    </body>
</html>