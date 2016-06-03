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
            <a class="navbar-brand" href="<?= base_url() ?>">教学资源云平台</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="<?= base_url() ?>">主页</a></li>
                <li><a href="<?= base_url('index.php/about')?>">About</a></li>
                <li class="active"><a href="<?= base_url('index.php/course_list')?>">课程</a></li>
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
<br/>
<br/>
<br/>
<div class="alert alert-info row" role="alert">
    <form class="form-inline col-sm-5" method="get" action="<?= base_url() . 'index.php/Course_list/'?>">
        <div>课程列表</div>
        <div class="form-group">
            <input type="text" class="form-control" name="lesson_search">
        </div>
        <button type="submit" class="btn btn-default">搜索</button>
    </form>
</div>
<?= print_r($course_list) ?>
<?php if (is_array($course_list)): ?>
    <div class="row">
        <?php foreach ($course_list as $key => $value): ?>
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                    <?php if (empty($value['lesson_image'])):?>
                        <img src="img/default.jpg" alt="...">
                    <?php else: ?>
                        <img src="<?= $value['lesson_image'] ?>" alt="...">
                    <?php endif; ?>
                    <div class="caption">
                        <h3><?= $value['lesson_name']?></h3>
                        <p><?=  $value['user_name'] ?>老师</p>
                        <p><a class="btn btn-primary" role="button" href='<?= base_url('index.php/Course_join/index/' . $value['lesson_group_id'] . '/1') ?>' target="_blank" ">参加课程</a></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<script src="http://nws.oss-cn-qingdao.aliyuncs.com/jquery.min.js"></script>
<script src="http://nws.oss-cn-qingdao.aliyuncs.com/bootstrap.min.js"></script>

<script src="<?= base_url('js/jquery.form.js')?>"></script>
<script src="<?= base_url('js/json.js')?>"></script>
<script>

</script>
</body>
</html>