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


    /**
     * 搜索课程
     */
    public function searchCourse(){
        $this->load->library('session');
        $this->load->library('authorizee');
        $this->load->library('util/Uuid');

        if (!$this->authorizee->CheckAuthorizee($this->session->userdata('user_role'), 'course_add')){
            echo json_encode(array('code' => -1, 'error' => '抱歉，您的权限不足'));
            exit;
        }

        $this->load->model('Course_model');
        $arrCourseRet = $this->Course_model->searchCourse($this->input->post('search_text', true), null, true);

        $arrCourseGroupData = array();

        foreach ($arrCourseRet as $arrCourseData){
            //以group_id为key
            $arrCourseGroupData[$arrCourseData['lesson_group_id']][$arrCourseData['lesson_level']] = $arrCourseData;
        }

        //需要补充获取level0列表，即上面的查询没有查到level 0主课内容
        $arrGetLevel0List   = array();
        foreach ($arrCourseGroupData as $intGroupId => $arrCourseGroupDataData){
            if (!isset($arrCourseGroupDataData[0])){
                $arrGetLevel0List[] = $intGroupId;
            }
        }

        //合并列表
        if (!empty($arrGetLevel0List)){
            $arrRetLevel0 = $this->Course_model->getMainCourse($arrGetLevel0List);
            foreach ($arrRetLevel0 as $arrRetLevel0Data){
                $arrCourseGroupData[$arrRetLevel0Data['lesson_group_id']][0] = $arrRetLevel0Data;
            }
        }

        echo json_encode($arrCourseGroupData);
    }
}