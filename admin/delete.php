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
                    <li class="active"><a href="#">删稿件</a></li>
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
if($_SESSION['uinfo']['previlege-delete'] == 1)
{
?>
    <!-- 删除一篇稿件的表单 -->
    <div class="thumbnail col-md-8 col-md-offset-2">
    <br>
    <h4>
        删除一篇稿件
    </h4>
    <br>
    <form class="form" action="delete-handler.php" method="post">
        <div class="form-group col-md-8 col-md-offset-2">
            <div class="input-group">
                <input id="page-url" class="form-control" type="text" placeholder="请输入稿件的源URL地址" required="required" name="url">
                </input>
                <span class="input-group-btn">
                    <button id="view-title" class="btn btn-default" type="button">预览</button>
                </span>
            </div>
        </div>
        <button id="view-btn" type="submit" class="btn btn-primary" disabled="disabled">确认删除</button>
        <div>
            <iframe id="view-page" src="" style="display:none" width=100% height=500px; seamless="seamless"></iframe>
        </div>
    </form>
    </div>
    <!-- 删除一篇稿件的表单，结束 -->
<?php
}
else
{
?>
    <div class="thumbnail col-md-8 col-md-offset-2">
    <br>
    <h4>
        删除一篇稿件
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

    <script type="text/javascript">
    $(document).ready(function(){
        $("#view-title").click(function(){
            if($("#page-url").val()){
                $("#view-page").attr("src",$("#page-url").val());
                $("#view-page").css("display","block");
                $("#view-btn").removeAttr("disabled");

            }
        });
    });
    </script>

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