<?php
$data = \action\statistics::$data['data'];
$Total = \action\statistics::$data['total'];
$currentPage = \action\statistics::$data['currentPage'];
$pagesize = \action\statistics::$data['pagesize'];
$keywords = \action\statistics::$data['keywords'];
$class = \action\statistics::$data['class'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <script type="text/javascript" src="js/jquery.js" ></script>
        <title>无标题文档</title>
        <script>
            $(function () {
                $('.button_find').click(function () {
                    window.location.href = 'index.php?a=<?php echo $class; ?>&m=index&keywords=' + $('.keywords').val();
                });
                $('.button_relation').click(function () {
                    window.location.href = 'index.php?a=<?php echo $class; ?>&m=setRelation&phone=' + $('.find_phone').val();
                });
            });
        </script>
    </head>

    <body>
        <div class="menu">
            <input type="text" name="keywords" class="keywords" value="<?php echo isset($keywords) ? $keywords : ""; ?>" />
            <a class="button_find " href="javascript:void(0);">查找</a>
            <input type="text" name="keywords" class="find_phone" value="" placeholder="查询已注册用户手机号码" />
            <a class="button_relation " href="javascript:void(0);">添加</a>
        </div>
        <div class="content">
            <table class="mytable" cellspacing="0" >
                <tr bgcolor="#656565" style=" font-weight:bold; color:#FFFFFF;">
                    <td class="td1" >用户名</td>
                    <td class="td1" width="20%">授权时间</td>
                    <td class="td1" width="30%">操作</td>
                </tr>
                <?php
                $sum_i = 1;
                if (!empty($data)) {
                    foreach ($data as $v) {
                        ?>
                        <tr<?php if ($sum_i % 2 != 1) { ?>  class="tr2"<?php } ?>>
                            <td class="td1"><?php echo $v['name']; ?></td>
                            <td class="td1"><?php echo $v['add_time']; ?></td>
                            <td class="td1">
                                <?php if (isset($v['euStatus'])) { ?>
                                    <?php if ($v['euStatus'] == 0) { ?>
                                        <a href="index.php?a=<?php echo $class; ?>&m=setEu&id=<?php echo $v['id']; ?>&status=1">允许加入</a> |
                                        <a href="index.php?a=<?php echo $class; ?>&m=setEu&id=<?php echo $v['id']; ?>&status=2">拒绝加入</a>
                                    <?php } else { ?>
                                        已加入 |
                                        <a href="index.php?a=<?php echo $class; ?>&m=setEu&id=<?php echo $v['id']; ?>&status=2">请离企业</a> |
                                        <a href="index.php?a=<?php echo $class; ?>&m=getCustomer&id=<?php echo $v['id']; ?>">查看</a>
                                    <?php } ?>
                                <?php } else { ?>
                                    <a href="index.php?a=<?php echo $class; ?>&m=getCustomer&id=<?php echo $v['id']; ?>">查看</a>
                                <?php } ?>
                            </td>
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
            $url = 'index.php?a=' . $class . '&m=index&keywords=' . $keywords;
            $Totalpage = ceil($Total / mod\init::$config['page_width']);
            include_once 'page.php';
            ?>
        </div>
    </body>
</html>
