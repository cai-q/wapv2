<?php
session_start();

include_once("../init.php");

function get_userlist()
{
    global $_SGLOBAL;
    $sql = "SELECT * FROM `user`";
    $result = $_SGLOBAL['db']->query($sql);

    $ans = array();

    while ($row = $_SGLOBAL['db']->fetch_array($result)) {
        $row['password'] = "********";
        $ans[] = $row;
    }

    return $ans;
}

if(isset($_SESSION['uinfo']) && isset($_SESSION['login']) && $_SESSION['login'] == 1)
{
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>荆楚网|移动站</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Loading Bootstrap -->
    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css">

    <!-- Loading Flat UI -->
    <link href="./dist/css/flat-ui.css" rel="stylesheet">

</head>
<body>
    <style>
    .navbar-static-top {
        margin-bottom: 19px;
    }
    </style>

    <!-- Static navbar -->
    <div class="navbar navbar-inverse navbar-static-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                </button>
                <a class="navbar-brand" href="#"><?=$_SESSION['uinfo']['name']?></a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li><a href="index.php">主页</a></li>
                    <li><a href="publish.php">发稿件</a></li>
                    <li><a href="delete.php">删稿件</a></li>
                    <li class="dropdown active">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">用户管理<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li class="active"><a href="#">用户列表</a></li>
                            <li class="divider"></li>
                            <li><a href="useradd.php">新增用户</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="password.php">修改密码</a></li>
                    <li><a href="logout-handler.php">登出</a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>

    <!-- 这里是主要内容 -->
<?php
if($_SESSION['uinfo']['superuser'] == 1)
{
    $userlist = get_userlist();
?>
    <!-- 发布一篇稿件的表单 -->
    <div class="thumbnail col-md-8 col-md-offset-2">
        <br>
        <h4>
            用户列表
        </h4>
        <br>
        <table class="table table-hover table-bordered">
            <tr>
                <th>账号</th>
                <th>姓名</th>
                <th>发稿件</th>
                <th>删稿件</th>
                <th>用户管理</th>
                <th>操作</th>
            </tr>
            <?php
            foreach ($userlist as $user)
            {
            ?>
                <tr>
                    <td><?=$user['account']?></td>
                    <td><?=$user['name']?></td>
                    <td>
                        <?php
                        if($user['previlege-publish'] == 1)
                        {
                            ?><span class="glyphicon glyphicon-ok"></span><?php
                        }
                        else
                        {
                            ?><span class="glyphicon glyphicon-remove"></span><?php
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if($user['previlege-delete'] == 1)
                        {
                            ?><span class="glyphicon glyphicon-ok"></span><?php
                        }
                        else
                        {
                            ?><span class="glyphicon glyphicon-remove"></span><?php
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if($user['superuser'] == 1)
                        {
                            ?><span class="glyphicon glyphicon-ok"></span><?php
                        }
                        else
                        {
                            ?><span class="glyphicon glyphicon-remove"></span><?php
                        }
                        ?>
                    </td>
                    <td>
                        <a class="btn btn-primary"
                        <?php
                        if(!($_SESSION['uinfo']['superuser'] == 1 && $user['superuser'] == 0))
                        {
                            ?>disabled="disabled"<?php
                        }
                        ?>
                        href="usercontrol.php?account=<?=$user['account']?>"><span class="glyphicon glyphicon-pencil"></span></a>
                        <a class="btn btn-primary"
                        <?php
                        if(!($_SESSION['uinfo']['superuser'] == 1 && $user['superuser'] == 0))
                        {
                            ?>disabled="disabled"<?php
                        }
                        ?>
                        href="userdelete.php?account=<?=$user['account']?>"><span class="glyphicon glyphicon-trash"></span></a>
                    </td>
                </tr>
            <?php
            }
            ?>
                <tr>
                    <td>
                        <a class="btn btn-primary"
                        <?php
                        if(!($_SESSION['uinfo']['superuser'] == 1))
                        {
                            ?>disabled="disabled"<?php
                        }
                        ?>
                        href="useradd.php"><span class="glyphicon glyphicon-plus"></span>新增用户</a>
                    </td>
                    <td>----</td>
                    <td>----</td>
                    <td>----</td>
                    <td>----</td>
                    <td>----</td>
                </tr>
        </table>
    </div>
    <!-- 发布一篇稿件的表单，结束 -->
<?php
}
else
{
?>
    <div class="thumbnail col-md-8 col-md-offset-2">
        <br>
        <h4>
            权限管理
        </h4>
        <br>
        <p class="text-danger">
            你没有权限这样做
        </p>
    </div>
<?php
}
?>
    <!-- 主要内容结束 -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="./dist/js/vendor/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="./dist/js/flat-ui.min.js"></script>


</body>
</html>
<?php
}
else
{
    header("Location:login.html");
    exit();
}

include_once("../exit.php");
?>