<?php
/**
 * 用于显示课程内容
 *
 *
 * Created by PhpStorm.
 * User: lin
 * Date: 16-5-18
 * Time: 下午3:41
 */
class Course_join extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
        $this->load->library('session');
        if (!$this->session->userdata('user_id')){
            header("Content-type: text/html; charset=utf-8");
            echo '<script>alert("您好，请登录");window.location.href="' . base_url() . '";</script>';
            return 0;
        }

        $this->load->model('Course_model');

        //区分用户角色
        $normalUser     = 0;
        $arrUserSchool  = array();
        if ('普通用户' == $this->session->userdata('user_role')){
            $normalUser    = 1;
            $arrUserSchool = json_decode($this->session->userdata('user_school'), true);
        }

//        $intGroup = $this->input->post('lesson_group_id', true);
        $intGroup = 1463563661134;
//        $intGroup = 1463546246124444;
//        $intLevel = $this->input->post('lesson_level',    true);
        $intLevel = 0;
        if (is_int($intLevel) || $intLevel <= 0){
            $intLevel = 1;
        }

        $arrCouseInfo = $this->Course_model->getCourseInfo($intGroup, $intLevel, $normalUser, $arrUserSchool);
        if (false === $arrCouseInfo){
            header("Content-type: text/html; charset=utf-8");
            echo '<script>alert("此课程不存在或为私有课程"); window.location.href="' . base_url() . '";</script>';
            return 0;
        }

        $arrCourseRes = json_decode($arrCouseInfo[$intLevel]['lesson_res'], true);
        if (empty($arrCouseInfo[$intLevel]['lesson_res_attach'])){
            $arrCourseAttach = array();
        } else {
            $arrCourseAttach = json_decode($arrCouseInfo[$intLevel]['lesson_res_attach'], true);
        }


        $this->load->view('course_join_view',
            array(
                'course_info'   => $arrCouseInfo,
                'course_level'  => $intLevel,
                'course_res'    => $arrCourseRes,
                'course_attach' => $arrCourseAttach,
            ));
    }

}