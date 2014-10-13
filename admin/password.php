<?php
session_start();

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
                    <li class="active"><a href="#">修改密码</a></li>
                    <li><a href="logout-handler.php">登出</a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>

    <!-- 这里是主要内容 -->
    <!-- 发布一篇稿件的表单 -->
    <div class="thumbnail col-md-8 col-md-offset-2">
    <br>
    <h4>
        更改密码
    </h4>
    <p class="text-info"><span class="glyphicon glyphicon-info-sign "></span>&nbsp;&nbsp;修改成功后会自动登出，请使用新密码重新登录</p>
    <br>
        <form class="form" action="password-handler.php" method="post">
                <div class="form-group col-md-8 col-md-offset-2">
                    <label class="control-label" for="inputWarning2">请输入原始密码</label>
                    <input type="password" class="form-control" name="old-password">
                </div>
                <div id="d1" class="form-group has-feedback col-md-8 col-md-offset-2">
                    <label id="l1" class="control-label" for="inputWarning2">请输入新密码</label>
                    <input id="i1" type="password" class="form-control" name="new-password">
                    <span id="s1" class="glyphicon glyphicon-warning-sign form-control-feedback"></span>
                </div>
                <div id="d2" class="form-group has-feedback col-md-8 col-md-offset-2">
                    <label id="l2" class="control-label" for="inputError2">再次输入新密码</label>
                    <input id="i2" type="password" class="form-control">
                    <span id="s2" class="glyphicon glyphicon-warning-sign form-control-feedback"></span>
                </div>
            <button id="btn" type="submit" class="btn btn-primary col-md-3 col-md-offset-7" disabled="disabled">确认修改</button>
        </form>
    </div>
    <!-- 发布一篇稿件的表单，结束 -->

    <!-- 主要内容结束 -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="./dist/js/vendor/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="./dist/js/flat-ui.min.js"></script>

    <script src="../statics/js/ronaldo-password.js"></script>
</body>
</html>
<?php
}
else
{
    header("Location:login.html");
    exit();
}
?>