<?php
$data = \action\statistics::$data['data'];
$Total = \action\statistics::$data['total'];
$currentPage = \action\statistics::$data['currentPage'];
$pagesize = \action\statistics::$data['pagesize'];
$keywords = \action\statistics::$data['keywords'];
$startTime = \action\statistics::$data['startTime'];
$endTime = \action\statistics::$data['endTime'];
$class = \action\statistics::$data['class'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <script type="text/javascript" src="js/jquery.js" ></script>
        <title>无标题文档</title>
        <!-- 日历插件 -->
        <link href="./css/bootstrap.min.css" rel="stylesheet" media="screen" />
        <link href="./css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen" />
        <script type="text/javascript" src="./js/bootstrap.min.js"></script>
        <script type="text/javascript" src="./js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
        <script type="text/javascript" src="./js/locales/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>
        <!-- 日历插件 end -->
        <script>
            $(function () {
                $('.button_find').click(function () {
                    window.location.href = 'index.php?a=<?php echo $class; ?>&m=customerList&keywords=' + $('.keywords').val();
                });
                $('.button_time').click(function () {
                    window.location.href = 'index.php?a=<?php echo $class; ?>&m=customerList&startTime=' + $('.start_date').val() + '&endTime=' + $('.end_date').val();
                });
                // 日历插件
                $('#start_date').datetimepicker({
                    language: 'zh-CN',
                    weekStart: 1,
                    todayBtn: 1,
                    autoclose: 1,
                    todayHighlight: 1,
                    startView: 2,
                    minView: 2,
                    forceParse: 0,
                    format: 'yyyy-mm-dd'
                });
                $('#end_date').datetimepicker({
                    language: 'zh-CN',
                    weekStart: 1,
                    todayBtn: 1,
                    autoclose: 1,
                    todayHighlight: 1,
                    startView: 2,
                    minView: 2,
                    forceParse: 0,
                    format: 'yyyy-mm-dd'
                });
            });
        </script>
    </head>

    <body>
        <div class="menu">
            <input type="text" name="keywords" class="keywords" value="<?php echo isset($keywords) ? $keywords : ""; ?>" />
            <a class="button_find " href="javascript:void(0);">查找</a>
            <input type="text" name="start_date" id="start_date" class="start_date" value="<?php echo isset($startTime) ? $startTime : ""; ?>" readonly /> -
            <input type="text" name="end_date" id="end_date" class="end_date" value="<?php echo isset($endTime) ? $endTime : ""; ?>" readonly />
            <a class="button_time " href="javascript:void(0);">查找</a>
        </div>
        <div class="content">
            <table class="mytable" cellspacing="0" >
                <tr bgcolor="#656565" style=" font-weight:bold; color:#FFFFFF;">
                    <td class="td1" >姓名</td>
                    <td class="td1" width="20%">最后登录时间</td>
                    <td class="td1" width="8%">必修课数</td>
                    <td class="td1" width="8%">选修课数</td>
                    <td class="td1" width="10%">总学习课程</td>
                    <td class="td1" width="8%">完成课程</td>
                </tr>
                <?php
                $sum_i = 1;
                if (!empty($data)) {
                    foreach ($data as $v) {
                        ?>
                        <tr<?php if ($sum_i % 2 != 1) { ?>  class="tr2"<?php } ?>>
                            <td class="td1"><?php echo $v['name']; ?></td>
                            <td class="td1"><?php echo $v['last_login_time']; ?></td>
                            <td class="td1"><?php echo $v['necessary']; ?></td>
                            <td class="td1"><?php echo $v['unnecessary']; ?></td>
                            <td class="td1"><?php echo $v['learned']; ?></td>
                            <td class="td1"><?php echo $v['finished']; ?></td>
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
            $url = 'index.php?a=' . $class . '&m=customerList&keywords=' . $keywords . '&startTime=' . $startTime . '&endTime=' . $endTime;
            $Totalpage = ceil($Total / mod\init::$config['page_width']);
            include_once 'page.php';
            ?>
        </div>
    </body>
</html>
