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
if($_SESSION['uinfo']['superuser'] == 1)
{
    $userlist = get_userlist();
?>
    <div class="thumbnail col-md-8 col-md-offset-2">
        <br>
        <h4>
            用户管理
        </h4>
        <br>
        <form class="form" action="usercontrol-handler.php" method="post">
            <?php
            if(isset($_GET['account']))
            {
                $user;
                foreach ($userlist as $curr_user) {
                    if($curr_user['account'] == $_GET['account'])
                    {
                        $user = $curr_user;
                        break;
                    }
                }
            ?>
            <div class="form-group col-md-8 col-md-offset-2">
                <label class="col-md-2" for="name">账户</label>
                <div class="col-md-10">
                    <input type="text" class="form-control" id="name" name="account" value="<?=$user['account']?>"></input>
                </div>
            </div>
            <div class="form-group col-md-8 col-md-offset-2">
            <br><br>
                <label class="col-md-2" for="name">姓名</label>
                <div class="col-md-10">
                    <input type="text" class="form-control" id="name" name="name" value="<?=$user['name']?>"></input>
                </div>
            </div>
            <br><br>
                <div id="d1" class="form-group has-feedback has-success col-md-8 col-md-offset-2">
                    <label id="l1" class="col-md-2" for="i1">密码</label>
                    <div class="col-md-10">
                        <input id="i1" type="password" class="form-control" name="password" required="required" value="<?=$user['password']?>" placeholder="请输入密码"></input>
                        <span id="s1" class="glyphicon glyphicon-ok form-control-feedback"></span>
                    </div>
                </div>
                 <div id="d2" class="form-group has-feedback has-success col-md-8 col-md-offset-2">
                    <label id="l2" class="col-md-2" for="i2">重复密码</label>
                    <div class="col-md-10">
                        <input id="i2" type="password" class="form-control" required="required" value="<?=$user['password']?>" placeholder="请再次输入密码"></input>
                        <span id="s2" class="glyphicon glyphicon-ok form-control-feedback"></span>
                    </div>
                </div>
            <br><br>
                <div class="form-group col-md-8 col-md-offset-2">
                <label class="col-md-2" for="previlege-publish">发稿权限</label>
                    <input type="checkbox" class="custom-checkbox" id="previlege-publish" name="previlege[]" value="previlege1"
                <?php
                if($user['previlege-publish'] == 1)
                {
                    ?>checked="checked"<?php
                }
                ?>
                ></input>
                <br><br>
                <label class="col-md-2" for="previlege-delete">删稿权限</label>
                <input type="checkbox" class="custom-checkbox" id="previlege-delete" name="previlege[]" value="previlege2"
                <?php
                if($user['previlege-delete'] == 1)
                {
                    ?>checked="checked"<?php
                }
                ?>
                ></input>
                </div>
            <button id="btn" type="submit" class="btn btn-primary col-md-3 col-md-offset-7">确认修改</button>
            </div>
            <?php
            }
            ?>
        </form>
    </div>
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

    <script src="../statics/js/ronaldo-password.js"></script>


    <script type="text/javascript">
    $(document).ready(function(){
        $("#view-btn").click(function(){
            if($("#account").val()){
                window.location.href = "usercontrol.php?account=" + $("#account").val();
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

include_once("../exit.php");
?>