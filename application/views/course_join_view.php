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

    <div class="row">
        <div class="col-sm-8" id="left_res">
        <?php if('video' == $course_res['type']): $showRes = 1; ?>
            <video controls width="100%">
                <source src="<?= $course_res['url'] ?>" type="video/mp4">
                <!--<source src="http://bucket.bricksfx.cn/file.php?file=a81da1b03bca4295f15c58139d85ab8396d64875" type="video/mp4">-->
                Your browser does not support the video tag.
            </video>
        <?php endif;?>
        <?php if('image' == $course_res['type']): $showRes = 1; ?>
            <img width="100%" src="<?= $course_res['url'] ?>">
        <?php endif; ?>
        <?php if ('audio' == $course_res['type']): $showRes = 1; ?>
            <audio controls width="100%">
                <source src="<?= $course_res['url']?>">
            </audio>
        <?php elseif ('application' == $course_res['type'] && 'pdf' == $course_res['ext']): $showRes = 1; ?>
            <embed width="100%" height="600px" type="application/pdf" src="<?= $course_res['url'] ?>"> </embed>
        <?php endif;?>

        <?php if (!isset($showRes)): ?>
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">找不到播放器预览教学资源</h3>
                </div>
                <div class="panel-body">
                    <a target="_blank" href="<?= $course_res['url'] ?>">请点击此处下载<?= $course_res['name'] ?>文档进行浏览</a>
                </div>
            </div>
        <?php endif; ?>
        </div>
        <div class="col-sm-4" id="right_res">
            <ul class="list-group">
                <a class="list-group-item active">随堂资料</a>
                <?php foreach ($course_attach as $courseAttachValue): ?>
                    <a class="list-group-item" target="_Blank" href="<?= $courseAttachValue['url'] ?>"><?= $courseAttachValue['name'] ?></a>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading"><?= $course_info[0]['lesson_name'] ?></div>
        <div class="panel-body">
            <?= $course_info[0]['lesson_intro'] ?>
        </div>
        <table class="table table-hover">
            <th>子课程列表</th>
            <?php $sizeCourse = count($course_info);
                for ($i = 1; $i < $sizeCourse; ++$i): ?>
                <tr>
                    <?php if ($i == $course_level): ?>
                        <td class="success">
                    <?php else: ?>
                        <td>
                    <?php endif; ?>
                <a href="<?= base_url('index.php/Course_join/index/' . $course_info[$i]['lesson_group_id'] . '/' . $i)  ?>"><?= $course_info[$i]['lesson_name'] ?></a></td></tr>
            <?php endfor; ?>
        </table>
    </div>
    <div>

    </div>
</div>

<script src="http://nws.oss-cn-qingdao.aliyuncs.com/jquery.min.js"></script>
<script src="http://nws.oss-cn-qingdao.aliyuncs.com/bootstrap.min.js"></script>

<script src="<?= base_url('js/jquery.form.js')?>"></script>
<script src="<?= base_url('js/json.js')?>"></script>
<script>

</script>
</body>
</html>