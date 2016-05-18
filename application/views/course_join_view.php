<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>教学资源云平台</title>

    <!-- Bootstrap core CSS -->
    <link href="http://nws.oss-cn-qingdao.aliyuncs.com/bootstrap.min.css" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style type="text/css"></style></head>

<body>

<!-- Fixed navbar -->
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">教学资源云平台</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="#">主页</a></li>
                <li><a href="<?= base_url('index.php/about')?>">About</a></li>
                <li class="active"><a href="<?= base_url('index.php/course_join')?>">课程</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <!-- <li class="active"><a href="./"></a></li> -->
                <?php if (!$this->session->userdata('user_name')): ?>
                    <li class="active"><a id="login_button" href="#" onclick="showLogin()">登录/注册</a></li>
                <?php else: ?>
                    <li class="dropdown active">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?= htmlentities($this->session->userdata('user_name')) ?> <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <!-- <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>-->
                            <li class="divider"></li>
                            <!-- <li class="dropdown-header">Nav header</li> -->
                            <li><a id="logout" onclick="logOut()" href="#">注销</a></li>
                        </ul>
                    </li>
                <?php endif;?>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>

<div class="container">
    <br/>
    <br/>
    <br/>

    <?php if('video' == $course_res['type']): ?>
        <video controls width="420px">
            <source src="<?= $course_res['url'] ?>" type="video/mp4">
            <!--<source src="http://bucket.bricksfx.cn/file.php?file=a81da1b03bca4295f15c58139d85ab8396d64875" type="video/mp4">-->
            Your browser does not support the video tag.
        </video>
    <?php endif; ?>
</div>

<script src="http://nws.oss-cn-qingdao.aliyuncs.com/jquery.min.js"></script>
<script src="http://nws.oss-cn-qingdao.aliyuncs.com/bootstrap.min.js"></script>

<script src="<?= base_url('js/jquery.form.js')?>"></script>
<script src="<?= base_url('js/json.js')?>"></script>
<script>

</script>
</body>
</html>