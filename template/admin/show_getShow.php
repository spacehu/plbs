<?php
$data = \action\show::$data['data'];
$image = \action\show::$data['image'];
$list = \action\show::$data['list'];
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
            <form name="theForm" id="demo" action="./index.php?a=show&m=updateShow&id=<?php echo $data['id']; ?>" method="post" enctype='multipart/form-data'>
                <div class="pathA ">
                    <div class="leftA">
                        <div class="leftAlist" >
                            <span>NAME 标题</span>
                        </div>
                        <div class="leftAlist" >
                            <div class="r_row">
                                <input class="text" name="name" type="text" value="<?php echo isset($data['name']) ? $data['name'] : ''; ?>" />
                            </div>
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
            var ue = UE.getEditor('container');
        </script>
    </body>
</html>