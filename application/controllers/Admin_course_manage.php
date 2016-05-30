<?php
/**
 * 课程管理
 *
 * Created by PhpStorm.
 * User: lin
 * Date: 16-6-1
 * Time: 下午9:29
 */
class Admin_course_manage extends CI_Controller{

    public function __construct(){
        parent::__construct();
    }

    public function index(){
        $this->load->library('session');
        $this->load->library('authorizee');

        if (!$this->authorizee->CheckAuthorizee($this->session->userdata('user_role'), 'course_add')){
            header("Content-type: text/html; charset=utf-8");
            echo '<script>alert("抱歉，您的权限不足");window.location.href="' . base_url() . '";</script>';
            return 0;
        }

        //获取course
        $this->load->model('Course_model');
        $arrCourseListRet = $this->Course_model->getCourseList(0, array());

        $this->load->view('admin_course_manage_view', array('course_list' => $arrCourseListRet));
    }


    public function searchCourse(){
        $this->load->model('Course_model');

        $arrCourseRet = $this->Course_model->searchCourse('安全', null, true);

        $arrCourseGroupData = array();

        foreach ($arrCourseRet as $arrCourseData){
            //以group_id为key
            $arrCourseGroupData[$arrCourseData['lesson_group_id']] = $arrCourseData;
        }


    }
}