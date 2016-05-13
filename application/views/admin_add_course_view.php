<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta charset="utf-8">
    <link href="http://nws.oss-cn-qingdao.aliyuncs.com/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('ueditor/themes/default/css/umeditor.css')?>" type="text/css" rel="stylesheet">
</head>
<body>
<br/>
<form action="admin_add_course/setCourse" class="form-horizontal" role="form" id="form_add_course" method="post">
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
        <input type="submit" class="form-control btn btn-success" id="submit" value="提交">
    </div>
    <br/>
    <br/>
    <hr>
</form>
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
        var dom = {
            formAddCourse : $("#form_add_course"),
            addCourseLevel: $("#confirm_course_level_sum")
        };

        var func = {
            bindFunc : function(){
                func.bindSubmitForm();
                dom.addCourseLevel.bind('click', func.addCourseLevel);
            },

            bindSubmitForm : function(){
                var submitBtn = dom.formAddCourse.find('#submit');
                var options   = {
                    dataType    : "json",
                    beforeSubmit: function (){
                        submitBtn.attr("value", "正在提交中……请稍后");
                        submitBtn.attr("disabled", "disabled");
                    },
                    success     : function (data){
                        if (1 != data['code']){
                            alert(data['error']);
                        } else {
                            alert('添加成功');
                            dom.formAddCourse.resetForm();
                            dom.formAddCourse.find('#myEditor').html('');
                        }
                        submitBtn.removeAttr("disabled");
                        submitBtn.attr("value", "添加");
                    },
                    error       : function (msg){
                        console.log(msg);
                        alert("操作失败");
                        submitBtn.removeAttr("disabled");
                        submitBtn.attr("value", "添加");
                    }

                };
                dom.formAddCourse.ajaxForm(options);
            },

            addCourseLevel : function(){
                var courseLevelSetArea = dom.formAddCourse.find('#course_level_set');
                courseLevelSetArea.empty();
                var courseLevelSum     = dom.formAddCourse.find('#course_level_sum').val();
                for (var i = 1; i <= courseLevelSum; i++){
                    var domToAppend = '<div class="form-group"><label for="course_level_' + i + '" class="col-sm-2 control-label">子课名称</label>';
                    domToAppend    += '<div class="col-sm-9"><input type="text" class="form-control" name="course_level[' + i + '][name]" id="course_level_' + i + '"></div></div>';
                    //主资源上传
                    domToAppend    += '<div class="form-group"><label for="course_res_upload_' + i + '" class="col-sm-2 control-label">子课资源上传</label>';
                    domToAppend    += '<div class="col-sm-9"><input class="form-control"  type="file" name="course_res_upload[' + i + ']" id="course_res_upload_' + i + '"></div></div>';

                    //多资料上传
                    domToAppend    += '<div class="form-group"><label for="course_res_attach_upload_' + i + '" class="col-sm-2 control-label">子课多课件上传</label>';
                    domToAppend    += '<div class="col-sm-9"><input class="form-control" multiple="multiple"  type="file" name="course_res_attach_upload[' + i + ']" id="course_res_attach_upload_' + i + '"></div></div>';

                    domToAppend    += '<hr>';
                    courseLevelSetArea.append(domToAppend);
                }
            }
        };

        func.bindFunc();
    });
</script>
</html>