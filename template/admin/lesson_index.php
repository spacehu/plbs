<?php
$data = \action\lesson::$data['data'];
$Total = \action\lesson::$data['total'];
$currentPage = \action\lesson::$data['currentPage'];
$pagesize = \action\lesson::$data['pagesize'];
$keywords = \action\lesson::$data['keywords'];
$class = \action\lesson::$data['class'];
$course_id = \action\lesson::$data['course_id'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <title>无标题文档</title>
    </head>

    <body>

        <div class="menu">
            <a href="javascript:void(0);" class="updateButton"  onclick="javascript:parent.mainFrame.location.href = 'index.php?a=<?php echo $class; ?>&m=getLesson&course_id=<?php echo $course_id; ?>'">添加新课时</a>
        </div>
        <div class="content">
            <table class="mytable" cellspacing="0" >
                <tr bgcolor="#656565" style=" font-weight:bold; color:#FFFFFF;">
                    <td class="td1">名称</td>
                    <td class="td1" width="10%">状态</td>
                    <td class="td1" width="20%">操作</td>
                </tr>
                <?php
                $sum_i = 1;
                if (!empty($data)) {
                    foreach ($data as $v) {
                        ?>
                        <tr<?php if ($sum_i % 2 != 1) { ?>  class="tr2"<?php } ?>>
                            <td class="td1"><?php echo $v['name']; ?></td>
                            <td class="td1"><?php
                                if ($v['delete'] == 0) {
                                    echo '使用中';
                                } else {
                                    echo '已删除';
                                }
                                ?></td>
                            <td class="td1">
                                <a href="index.php?a=test&m=index&lesson_id=<?php echo $v['id']; ?>">试题</a>
                                | <a href="index.php?a=<?php echo $class; ?>&m=getLesson&course_id=<?php echo $course_id; ?>&id=<?php echo $v['id']; ?>">编辑</a>
                                | <a href="index.php?a=<?php echo $class; ?>&m=deleteLesson&id=<?php echo $v['id']; ?>" onclick="return confirm('确定将此课时删除?')">删除</a></td>
                        </tr>
                        <?php
                        $sum_i++;
                    }
                }
                ?>
            </table>
            <div class="num_bar">
                总数<b><?php echo $Total; ?></b>
            </div>
            <?php
            $url = 'index.php?a=' . $class . '&m=index&course_id=' . $course_id . '&keywords=' . $keywords;
            $Totalpage = ceil($Total / mod\init::$config['page_width']);
            include_once 'page.php';
            ?>
        </div>
    </body>
</html>
