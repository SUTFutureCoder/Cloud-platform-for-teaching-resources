<?php
/**
 * Created by PhpStorm.
 * User: lin
 * Date: 16-5-13
 * Time: 上午8:38
 *
 * 添加课程
 *
 */
class Admin_add_course extends CI_Controller{

    public function __construct()
    {
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

        $this->load->view('admin_add_course_view');
    }



    public function setCourse(){
        $this->load->library('session');
        $this->load->library('authorizee');

        if (!$this->authorizee->CheckAuthorizee($this->session->userdata('user_role'), 'course_add')){
            echo json_encode(array('code' => -1, 'data' => '抱歉，您的权限不足'))
            exit;
        }

        $tempCourseName  = $this->input->post('course_name', true);
        if (empty($tempCourseName) || isset($tempCourseName[1024])){
            echo json_encode(array('code' => -2, 'data' => '抱歉，传入的课程名称为空或超过1024个字符'));
            exit;
        }
        $clean['lesson_name']  = $tempCourseName;

        $tempCourseIntro = $this->input->post('course_intro', true);
        if (empty($tempCourseIntro) || isset($tempCourseIntro[1024])){
            echo json_encode(array('code' => -3, 'data' => '抱歉，传入的课程介绍为空或超过1024个字符'));
            exit;
        }
        $clean['lesson_intro'] = $tempCourseIntro;

        $tempLevelSum    = $this->input->post('course_level_sum', true);
        if (empty($tempLevelSum)){
            echo json_encode(array('code' => -4, 'data' => '抱歉，课程节数不能为空'));
        }
        $clean['']



        print_r($_POST);
        print_r($_FILES);
    }

}


