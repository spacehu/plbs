<?php
$data = \action\customer::$data['data'];
$Total = \action\customer::$data['total'];
$currentPage = \action\customer::$data['currentPage'];
$pagesize = \action\customer::$data['pagesize'];
$keywords = \action\customer::$data['keywords'];
$class = \action\customer::$data['class'];
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
                var ids = [];
                $('.button_find').click(function () {
                    window.location.href = 'index.php?a=<?php echo $class; ?>&m=index&keywords=' + $('.keywords').val();
                });
                $('.button_relation').click(function () {
                    window.location.href = 'index.php?a=<?php echo $class; ?>&m=setRelation&phone=' + $('.find_phone').val();
                });
                $('.button_allow_add').click(function () {
                    if (ids.length === 0) {
                        alert("请选择学员执行该操作");
                        return;
                    }
                    console.log(ids);
                    //alert("请选择学员执行该操作");
                    window.location.href = 'index.php?a=<?php echo $class; ?>&m=setEu&ids=' + ids + '&status=1';
                });
                $('.button_refuse_add').click(function () {
                    if (ids.length === 0) {
                        alert("请选择学员执行该操作");
                        return;
                    }
                    window.location.href = 'index.php?a=<?php echo $class; ?>&m=setEu&ids=' + ids + '&status=2';
                });
                $('.ids').on('click', function () {
                    if ($(this).attr("checked") == "checked") {
                        ids[ids.length] = $(this).attr("data-value");
                    } else {
                        ids.splice($.inArray($(this).attr("data-value"), ids), 1);
                        $("#allids").attr("checked", false);
                    }
                    //console.log(ids);
                });

                $('#allids').click(function () {
                    var check = 0;
                    $(".ids").each(function () {
                        if ($(this).attr("checked") == "checked") {
                            check++;
                        }
                    });
                    if (check == 0) {
                        $(".ids").each(function () {
                            $(this).attr("checked", "checked");
                            ids[ids.length] = $(this).attr("data-value");
                        });
                    } else {
                        $(".ids").attr("checked", false);
                        $(this).attr("checked", false);
                        ids = [];
                    }
                    //console.log(ids);
                });
                //ids 记录完成 需要送出去
            });
        </script>
    </head>

    <body>
        <div class="menu">
            <input type="text" name="keywords" class="keywords" value="<?php echo isset($keywords) ? $keywords : ""; ?>" placeholder="请输入用户名中的关键字" />
            <a class="button_find " href="javascript:void(0);">查找</a>
            <input type="text" name="keywords" class="find_phone" value="" placeholder="查询已注册用户手机号码" />
            <a class="button_relation " href="javascript:void(0);">添加</a>
            <a class="button_allow_add" href="javascript:void(0);">允许加入</a>
            <a class="button_refuse_add" href="javascript:void(0);">拒绝加入</a>
        </div>
        <div class="content">
            <table class="mytable" cellspacing="0" >
                <tr bgcolor="#656565" style=" font-weight:bold; color:#FFFFFF;">
                    <td class="td1" width="5%"><input type="checkbox" class="checkbox" id="allids" /></td>
                    <td class="td1" >用户名</td>
                    <td class="td1" >企业名</td>
                    <td class="td1" >部门名</td>
                    <td class="td1" >职位名</td>
                    <td class="td1" width="20%">授权时间</td>
                    <td class="td1" width="30%">操作</td>
                </tr>
                <?php
                $sum_i = 1;
                if (!empty($data)) {
                    foreach ($data as $v) {
                        ?>
                        <tr<?php if ($sum_i % 2 != 1) { ?>  class="tr2"<?php } ?>>
                            <td class="td1"><input type="checkbox" class="checkbox ids"  data-value="<?php echo $v['id']; ?>" /></td>
                            <td class="td1"><?php echo $v['name']; ?></td>
                            <td class="td1"><?php echo $v['eName']; ?></td>
                            <td class="td1"><?php echo $v['edName']; ?></td>
                            <td class="td1"><?php echo $v['epName']; ?></td>
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
