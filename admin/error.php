<?php
session_start();
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

    <link rel="shortcut icon" href="./dist/img/favicon.ico">
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
<!--[if lt IE 9]>
<script src="../../dist/js/vendor/html5shiv.js"></script>
<script src="../../dist/js/vendor/respond.min.js"></script>
<![endif]-->
</head>
<body>
    <style>

    .navbar-static-top {
        margin-bottom: 19px;
    }
    </style>
<?php

if(isset($_SESSION['uinfo']) && isset($_SESSION['login']) && $_SESSION['login'] == 1)
{
?>
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
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">用户管理<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="userlist.php">用户列表</a></li>
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
}
if(isset($_GET['ecode']))
{
?>
    <!-- 发布一篇稿件的表单 -->
    <div class="thumbnail col-md-8 col-md-offset-2 text-center">
        <br>
        <h4>
            <span class="glyphicon glyphicon-exclamation-sign"></span>
            出错了
        </h4>
        <br>
        <p>
            <?php
            switch ($_GET['ecode']) {
                case 'E01':
                    ?>请检查用户名密码是否正确<?php
                    break;
                case 'E02':
                    ?>请检查自己是否登录<?php
                    break;
                case 'E03':
                    ?>请不要输入站外地址<?php
                    break;
                case 'E04':
                    ?>账户已被注册，请更换账户名或联系管理员<?php
                    break;
                case 'E05':
                    ?>账户不存在<?php
                    break;
                case 'E06':
                    ?>密码过长<?php
                    break;
                case 'E06':
                    ?>原始密码错误<?php
                    break;
                default:
                    ?>未知错误<?php
                    break;
            }
            ?>
        </p>
        <br>
    </div>
    <!-- 发布一篇稿件的表单，结束 -->
<?php
}
else
{
?>
    <div class="thumbnail col-md-8 col-md-offset-2 text-center">
        <br>
        <h4>
            <span class="glyphicon glyphicon-exclamation-sign"></span>
            出错了
        </h4>
        <br>
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