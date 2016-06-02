<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link href="http://nws.oss-cn-qingdao.aliyuncs.com/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('ueditor/themes/default/css/umeditor.css')?>" type="text/css" rel="stylesheet">
</head>
<body>
<br/>
<div id="top" class="col-sm-offset-4">
    <form class="form-inline">
        <div class="form-group col-sm-6">
            <div class="input-group">
                <input type="text" class="form-control" id="top-search-text" placeholder="Search for...">
                    <span class="input-group-btn">
                        <button class="btn btn-success" id="top-search-submit" type="button">搜索</button>
                    </span>
            </div><!-- /input-group -->
        </div>
    </form>
</div>
<br/>
<hr>
<div id="content">
    <table class="table table-hover" id="content-table">
        <thead>
        <tr>
            <th>#</th>
            <th>课程名称</th>
            <th>教师</th>
            <th>是否私有</th>
            <th>添加时间</th>
            <th>管理</th>
        </tr>
        </thead>
        <tbody>
        <?php $i = 1; foreach ($course_list as $courseListData): ?>
            <tr id="data_course_group_id_<?= $courseListData['lesson_group_id'] ?>">
                <td data-><?= $i ?></td>
                <td><?= $courseListData['lesson_name'] ?></td>
                <td><?= $courseListData['user_name'] ?></td>
                <td><?= $courseListData['lesson_is_private'] ?></td>
                <td><?= date('Y-m-d H:i:s', $courseListData['lesson_ctime']) ?></td>
                <td data-course-list-id="<?= $i ?>" data-course-id="<?= $courseListData['lesson_group_id'] ?>"><button class="btn btn-warning course-view" onclick="window.open('<?= base_url() ?>index.php/Course_join/index/<?= $courseListData['lesson_group_id'] ?>/1')">预览</button><button class="btn btn-warning course-modify">修改</button><button class="btn btn-danger course-delete">删除</button></td>
            </tr>
        <?php ++$i; endforeach;?>
        </tbody>
    </table>
</div>

<div id="bottom" class="col-md-offset-5">

</div>

<div class="modal fade bs-example-modal-lg" id="course_modify_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <br/>
            <form action="admin_course_manage/modifyCourse" class="form-horizontal" role="form" id="form_modify_course" method="post">
                <input type="text" style="display: none;" name="course_group_id" id="course_group_id">
                <div class="form-group">
                    <label for="course_name" class="col-sm-2 control-label">课程名称</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="course_name" id="course_name">
                    </div>
                </div>

                <div class="form-group">
                    <label for="course_intro" class="col-sm-2 control-label">课程介绍</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="course_intro" id="myEditor" style="width:100%;height:240px;" rows="3"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="course_image_upload" class="col-sm-2 control-label">课程预览图上传</label>
                    <div class="col-sm-9">
                        <input class="form-control"  type="file" name="course_image_upload" id="course_image_upload">
                    </div>
                </div>
                <hr>


                <div class="form-group">
                    <label for="course_level_sum" class="col-sm-2 control-label">课程节数</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="course_level_sum" id="course_level_sum">
                    </div>
                </div>
                <div class="col-sm-10 col-sm-offset-1">
                    <input class="form-control btn btn-info" id="confirm_course_level_sum" value="生成课程">
                </div>
                <br/>
                <br/>
                <div class="form-group" id="course_level_set">

                </div>
                <br/>
                <hr>

                <div class="form-group">
                    <label for="course_is_private" class="col-sm-2 control-label">是否为私有</label>
                    <div class="col-sm-9">
                        <input type="checkbox" name="course_is_private" id="course_is_private" >仅限于[<?= implode(',', json_decode($this->session->userdata('user_school'), true))?>]学生使用
                    </div>
                </div>

                <br/>
                <br/>
                <hr>
                <div class="col-sm-10 col-sm-offset-1">
                    <input type="submit" class="form-control btn btn-success" id="submit" value="修改">
                </div>
                <br/>
                <br/>
                <hr>
            </form>
        </div>
    </div>
</div>




<div class="modal fade bs-example-modal-lg" id="course_delete_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <br/>
            <h1 style="color: red">您确定要删除第"<a id="course_delete_modal_display_id"></a>"门课程吗?</h1>
            <br/>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-danger" data-delete-course-group-id="" id="delete_course_submit">删除</button>
            </div>
        </div>
    </div>
</div>

</body>
<script src="http://nws.oss-cn-qingdao.aliyuncs.com/jquery.min.js"></script>
<script src="http://nws.oss-cn-qingdao.aliyuncs.com/bootstrap.min.js"></script>
<script type="text/javascript" charset="utf-8" src="<?= base_url('ueditor/umeditor.config.js') ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?= base_url('ueditor/umeditor.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('ueditor/lang/zh-cn/zh-cn.js') ?>"></script>
<script src="<?= base_url('js/jquery.form.js')?>"></script>
<script>
    //实例化编辑器
    var um = UM.getEditor('myEditor');

    $(function(){
        var dom  = {
            top     : $("#top"),
            search_text  : $("#top-search-text"),
            search_submit: $("#top-search-submit"),

            content : $("#content"),
            content_table: $("#content-table"),

            course_modify_modal: $("#course_modify_modal"),
            course_delete_modal: $("#course_delete_modal"),

            bottom  : $("#bottom")
        };

        var page = {
            //分页属性
            page_no : 1,
            perpage : 20
        };

        var funcInit = {
            init: function(){
                this.bindFunc();
            },

            bindFunc: function(){
                //绑定进行搜索事件
                dom.search_submit.bind('click',  this.submitSearch);

                //绑定点击修改、删除事件 (动态绑定)
                dom.content_table.on('click', '.course-modify', this.modifyCourse);
                dom.content_table.on('click', '.course-delete', this.deleteCourse);

                //执行删除操作
                dom.course_delete_modal.find('#delete_course_submit').bind('click', this.deleteCourseExec);
            },

            submitSearch: function () {
                var searchText = dom.search_text.val();

                if ('' == searchText){
                    alert('请输入搜索关键字');
                    return 0;
                }

                $.ajax({
                    type: 'POST',
                    url:  'admin_course_manage/searchCourse',
                    data: {
                        'search_text' : searchText
                    },
                    dataType: 'json',
                    success: function (data) {
                        if (data['code']){
                            alert(data['error']);
                            return 0;
                        }
                        //开始填充
                        funcInit.displayData(data, 0);
                    },
                    error: function(data){
                        alert('操作失败');
                    }
                });
            },

            modifyCourse: function(){
                //重设表单
                dom.question_modify_modal.find('#form_modify_question').resetForm();

                var questionId = $(this).parent().attr('data-question-id');
                //ajax请求
                $.ajax({
                    type: 'POST',
                    url:  'admin_question_manage/getQuestionInfoById',
                    data: {
                        'question_id' : questionId
                    },
                    dataType: 'json',
                    success: function (data) {
                        if (data['code']){
                            alert(data['error']);
                            return 0;
                        }
                        //开始填充
                        dom.question_modify_modal.find('#question_id').val(questionId);
                        dom.question_modify_modal.find('.question_type_select_option').removeAttr('selected');
                        dom.question_modify_modal.find('#question_type_select_' + data['question_type']).prop('selected', true);
                        dom.question_modify_modal.find('#question_content').html(data['question_content']);

                        //分情况
                        if (data['type'] == 'choose' || data['type'] == 'multi_choose'){
                            var questionNum = data['question_choose'].length;
                            dom.question_modify_modal.find('#question_num').val(questionNum);
                            //模拟点击，显示选项
                            dom.question_modify_modal.find('#confirm_question_num').trigger("click");
                            //填充选项
                            for (var i = 0; i < questionNum; i++){
                                dom.question_modify_modal.find('#question_choose_' + i).val(data['question_choose'][i]);
                            }
                            //正确答案
                            dom.question_modify_modal.find('#question_choose_answer').val(data['question_answer'].join(' '));
                        }

                        //目前没有
                        if (data['type'] == 'fill'){
                            dom.question_modify_modal.find('#question_fill_answer').val(data['question_answer']);
                        }

                        if (data['type'] == 'judge'){
                            dom.question_modify_modal.find('#question_judge').prop('checked', true);
                            if (1 == data['question_answer']){
                                dom.question_modify_modal.find('#question_judge_true').prop('checked', true);
                            }
                        }

                        dom.question_modify_modal.find('#question_score').val(data['question_score']);

                        if (1 == data['question_private']){
                            dom.question_modify_modal.find('#question_private').prop('checked', true);
                        }

                        if (data['question_hint']){
                            dom.question_modify_modal.find('#myEditor,.edui-body-container,#question_hint').html(data['question_hint']);
                        }


                        //绑定提交选项
                        var options = {
                            dataType    : "json",
                            beforeSubmit: function (){
                                dom.question_modify_modal.find("#submit").attr("value", "正在提交中……请稍后");
                                dom.question_modify_modal.find("#submit").attr("disabled", "disabled");
                            },
                            success     : function (data){
                                if (1 != data['code']){
                                    alert(data['error']);
                                } else {
                                    alert('修改成功');
                                    //修改信息
                                    var content = dom.content_table.find('#data_question_id_' + questionId);
                                    content.find('td:eq(1)').html(dom.question_modify_modal.find('#question_content').val());
                                    if (!dom.question_modify_modal.find('#question_type_fill').val()){
                                        content.find('td:eq(3)').html(dom.question_modify_modal.find('#question_type_select').val());
                                    } else {
                                        content.find('td:eq(3)').html(dom.question_modify_modal.find('#question_type_fill').val());
                                    }

                                    dom.question_modify_modal.find("#form_modify_question").resetForm();
                                    dom.question_modify_modal.find('#myEditor').html('');
                                }

                                dom.question_modify_modal.modal('hide');

                                dom.question_modify_modal.find("#submit").removeAttr("disabled");
                                dom.question_modify_modal.find("#submit").attr("value", "修改");
                            },
                            error       : function (msg){
                                console.log(msg);
                                alert("操作失败");
                                dom.question_modify_modal.find("#submit").removeAttr("disabled");
                                dom.question_modify_modal.find("#submit").attr("value", "修改");
                            }
                        };
                        dom.question_modify_modal.find("#form_modify_question").ajaxForm(options);
                        dom.question_modify_modal.modal('show');
                    },
                    error: function(data){
                        alert('操作失败');
                    }
                });
            },

            deleteCourse: function(){
                var questionListId = ($(this).parent().attr('data-question-list-id') * 1) + 1;
                dom.question_delete_modal.find('#question_delete_modal_display_id').html(questionListId);
                dom.question_delete_modal.find('#delete_question_submit').attr('data-question-id', $(this).parent().attr('data-question-id'));
                dom.question_delete_modal.modal('show');
            },

            deleteCourseExec: function(){
                var questionId = $(this).attr('data-question-id');
                //ajax请求
                $.ajax({
                    type: 'POST',
                    url:  'admin_question_manage/deleteQuestionById',
                    data: {
                        'question_id' : questionId
                    },
                    dataType: 'json',
                    success: function (data) {
                        if (data['code'] != 1){
                            alert(data['error']);
                            return 0;
                        }
                        alert('删除成功');
                        //开始清理
                        dom.content_table.find('#data_question_id_' + questionId).remove();
                        dom.question_delete_modal.modal('hide');
                    },
                    error: function(data){
                        alert('操作失败');
                    }
                });
            },

            displayData: function(data, divide, sum){
                //用于统一显示数据
                var contentTableBody = dom.content_table.find('tbody');

                contentTableBody.html('');

                var i = 0;
                for (var lessonData in data){
                    var strData = '<tr id="data_course_group_id_' + data[lessonData][0]['lesson_group_id'] + '"><td>' + (i + 1) + '</td>' +
                        '<td>' + data[lessonData][0]['lesson_name'] + '</td>' +
                        '<td>' + data[lessonData][0]['user_name'] + '</td>' +
                        '<td>' + data[lessonData][0]['lesson_is_private'] + '</td>' +
                        '<td>' + data[lessonData][0]['lesson_ctime'] + '</td>' +
                        '<td data-course-list-id="' + i + '" data-course-id="' + data[lessonData][0]['lesson_group_id'] + '"><button class="btn btn-warning course-view" onclick="window.open(\'<?= base_url() ?>index.php/Course_join/index/' + data[lessonData][0]['lesson_group_id'] + '/1\')">预览</button><button class="btn btn-warning course-modify">修改</button><button class="btn btn-danger course-delete">删除</button></td></tr>';
                    contentTableBody.append(strData);
                    ++i;
                }

                if (divide) {
                    //进行分页
                    funcInit.dividePage(sum);
                } else {
                    //隐藏分页条
                    dom.bottom.hide();
                }
            },

            dividePage : function(sum){
                //如果分页,则显示页码等信息
                //清空分页条
                dom.bottom.html('');
                //计算一共几页
                var pageSum      = Math.ceil((sum * 1) / page.perpage);
                //算出展示的页码，5个一组，12345,34567
                var pageBtnShow  = 5;
                var firstPageBtn = 1;
                var lastPageBtn  = pageSum;
                //顶头情况
                if (page.page_no - 1 <= (pageBtnShow - 1) / 2){
                    firstPageBtn = 1;
                    if (pageBtnShow <= pageSum){
                        lastPageBtn  = pageBtnShow;
                    } else {
                        lastPageBtn  = pageSum;
                    }
                } else if (pageSum - page.page_no <= (pageBtnShow - 1) / 2){
                    //结尾情况
                    lastPageBtn  = pageSum;
                    if (lastPageBtn  - pageBtnShow > 0){
                        firstPageBtn = lastPageBtn - pageBtnShow + 1;
                    } else {
                        firstPageBtn = 1;
                    }
                } else {
                    //中间情况
                    firstPageBtn = page.page_no * 1 - (pageBtnShow - 1) / 2;
                    lastPageBtn  = page.page_no * 1 + (pageBtnShow - 1) / 2;
                }

                var strDivide = '<nav><ul class="pagination">' +
                    '<li id="pager_prev"><a href="#" aria-label="Previous"><span aria-hidden="true">«</span></a></li>';

                for (var i = firstPageBtn; i <= lastPageBtn; ++i){
                    strDivide += '<li class="pager_changer" id="pager_num_' + i + '" data-pager-num="' + i + '"><a href="#">' + i + ' </a></li>';
                }

                strDivide += '<li id="pager_next"><a href="#" aria-label="Next"><span aria-hidden="true">»</span></a></li></ul></nav>';
                dom.bottom.html(strDivide);
                //开始染色
                if (firstPageBtn == page.page_no){
                    dom.bottom.find("#pager_prev").addClass('disabled');
                    dom.bottom.find('#pager_prev').off('click');
                } else {
                    //绑定上一页
                    dom.bottom.find('#pager_prev').on('click', function(){
                        page.page_no--;
                        funcInit.changeQuestionBank();
                    });
                }
//
                if (lastPageBtn == page.page_no){
                    dom.bottom.find('#pager_next').addClass('disabled');
                    dom.bottom.find('#pager_next').off('click');
                } else {
                    //绑定下一页
                    dom.bottom.find('#pager_next').on('click', function(){
                        page.page_no++;
                        funcInit.changeQuestionBank();
                    });
                }

                dom.bottom.find("#pager_num_" + page.page_no).addClass('active');

                //遍历绑定
                dom.bottom.find('.pager_changer').each(function () {
                    $(this).on('click', function(){
                        page.page_no = $(this).attr('data-pager-num');
                        funcInit.changeQuestionBank();
                    });
                });

                //显示分页条
                dom.bottom.show();

            },

            resetPage: function(){
                //重设分页
                page.page_no = 1;
                page.perpage = 20;
            }
        };

        funcInit.init();
    });
</script>
</html>